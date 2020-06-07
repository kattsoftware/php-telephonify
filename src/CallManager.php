<?php

namespace KattSoftware\Telephonify;

use KattSoftware\Telephonify\Drivers\DriverInterface;
use KattSoftware\Telephonify\Exceptions\TelephonifyException;
use KattSoftware\Telephonify\SessionStorage\SessionStorageInterface;
use KattSoftware\Telephonify\SessionStorage\SessionStorageException;
use KattSoftware\Telephonify\Drivers\Exceptions\FeatureNotAvailableException;
use KattSoftware\Telephonify\Drivers\Exceptions\DriverInternalException;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
class CallManager
{
    /** @var DriverInterface */
    private $driver;

    /** @var SessionStorageInterface */
    private $sessionStorage;

    /**
     * Call manager constructor.
     *
     * @param DriverInterface $driver
     * @param SessionStorageInterface $sessionStorage
     */
    public function __construct(DriverInterface $driver, SessionStorageInterface $sessionStorage)
    {
        $this->driver = $driver;
        $this->sessionStorage = $sessionStorage;
    }

    /**
     * Redirect an ongoing call from its current state/controller, to another IVRController.
     * Requires the driver to be configured for making external API calls.
     *
     * @param string $endpointUrl
     * @param string $callId
     * @param string $redirectTo
     * @throws TelephonifyException
     */
    public function redirectCall($endpointUrl, $callId, $redirectTo)
    {
        if (!class_exists($redirectTo) && is_subclass_of($redirectTo, IVRController::class)) {
            throw new TelephonifyException('Attempting to perform a redirect to a non-IVRController class: ' . $redirectTo);
        }

        try {
            $savedState = $this->sessionStorage->readState($callId);
        } catch (SessionStorageException $e) {
            throw new TelephonifyException('The session could not be read for call ID  = ' . $callId);
        }

        $callState = CallState::createFromState(json_decode($savedState, true));
        $callState->setContinueTo([$redirectTo]);

        try {
            $this->sessionStorage->writeState($callId, json_encode($callState));
        } catch (SessionStorageException $e) {
            throw new TelephonifyException('The session could not be written for call ID  = ' . $callId);
        }

        try {
            $this->driver->redirectCall($callId, $endpointUrl);
        } catch (FeatureNotAvailableException $e) {
            throw new TelephonifyException('The feature is not available for this configuration', 0, $e);
        } catch (DriverInternalException $e) {
            throw new TelephonifyException('The driver failed to create the call', 0, $e);
        }
    }

    /**
     * Create an outgoing call.
     * Requires the driver to be configured for making external API calls.
     *
     * @param string $endpointUrl Full URL endpoint where Application::processOutgoingCall is called and its result returned
     * @param string $eventUrl Full URL endpoint where Application::processEventRequest is called and its result returned
     * @param string $to The number to be called
     * @param string $from The number who is calling (must be one of your provider's bought call numbers)
     * @param string $startingControllerClass Fully qualified class name (instance of IVRController), which will start controlling the call
     * @param int $ringingTimeout Ringing timeout in seconds
     * @throws TelephonifyException
     */
    public function createCall($endpointUrl, $eventUrl, $to, $from, $startingControllerClass, $ringingTimeout = 60)
    {
        $tempCallId = self::computeOutgoingCallHash(get_class($this->driver), $to, $from);

        if (!class_exists($startingControllerClass) && is_subclass_of($startingControllerClass, IVRController::class)) {
            throw new TelephonifyException('Attempting to create a call with a non-IVRController class: ' . $startingControllerClass);
        }

        // Create the state and save it PRE-calling any API
        $callState = new CallState();
        $callState->setContinueTo([$startingControllerClass]);
        $callState->setCallingNumber($from);
        $callState->setCallerNumber($to);

        try {
            $this->sessionStorage->writeState($tempCallId, json_encode($callState));
        } catch (SessionStorageException $e) {
            throw new TelephonifyException('The session could not be written', 0, $e);
        }

        try {
            $this->driver->createOutgoingCall(
                $endpointUrl,
                $eventUrl,
                $from,
                $to,
                $ringingTimeout
            );
        } catch (FeatureNotAvailableException $e) {
            throw new TelephonifyException('The feature is not available for this configuration', 0, $e);
        } catch (DriverInternalException $e) {
            throw new TelephonifyException('The driver failed to create the call', 0, $e);
        }
    }

    /**
     * Compute a temporary outgoing call "id"
     *
     * @param string $driverName
     * @param string $to
     * @param string $from
     * @return string
     */
    public static function computeOutgoingCallHash($driverName, $to, $from)
    {
        return md5("OUT-{$driverName}-{$to}-{$from}");
    }
}
