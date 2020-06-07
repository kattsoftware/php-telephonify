<?php

namespace KattSoftware\Telephonify\Drivers;

use KattSoftware\Telephonify\Drivers\Events\TwilioEventsProcessor;
use KattSoftware\Telephonify\Drivers\Exceptions\DriverInternalException;
use KattSoftware\Telephonify\Drivers\Exceptions\FeatureNotAvailableException;
use KattSoftware\Telephonify\Drivers\Response\TwilioResponseBuilder;
use KattSoftware\Telephonify\Drivers\Security\TwilioSignatureValidator;
use KattSoftware\Telephonify\Exceptions\SecurityViolationException;
use KattSoftware\Telephonify\Request;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;
use UnexpectedValueException;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
class Twilio implements DriverInterface
{
    const ANONYMOUS_NUMBER = '+266696687';

    /** @var null|Client */
    private $twilioClient;

    /** @var string|null */
    private $authToken;

    /**
     * @param Client $twilioClient
     */
    public function setTwilioClient(Client $twilioClient)
    {
        $this->twilioClient = $twilioClient;
    }

    /**
     * @param string $authToken
     */
    public function enableRequestSignatureValidation($authToken)
    {
        $this->authToken = $authToken;
    }

    /**
     * @inheritDoc
     */
    public function createRequest($url)
    {
        if (!isset($_SERVER['REQUEST_METHOD']) || strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
            throw new UnexpectedValueException('The request HTTP method is not POST');
        }

        if (!isset($_POST['CallSid'])) {
            throw new UnexpectedValueException('The HTTP request payload is invalid. Twilio parameter "CallSid" is not available.');
        }

        $this->checkIfRequestIsGenuine($url);

        return new Request(
            $_POST['CallSid'],
            $_POST['Caller'] !== self::ANONYMOUS_NUMBER ? ltrim($_POST['Caller'], '+') : null,
            ltrim($_POST['Called'], '+'),
            isset($_POST['Digits']) && $_POST['Digits'] !== '' ? $_POST['Digits'] : null,
            isset($_POST['Digits']) && $_POST['Digits'] === ''
        );
    }

    /**
     * @inheritDoc
     */
    public function createAnswerResponseBuilder()
    {
        return new TwilioResponseBuilder();
    }

    /**
     * @inheritDoc
     */
    public function createEventsProcessor($url)
    {
        $this->checkIfRequestIsGenuine($url);

        return new TwilioEventsProcessor($_POST);
    }

    /**
     * @inheritDoc
     */
    public function redirectCall($callId, $redirectUrl)
    {
        if ($this->twilioClient === null) {
            throw new FeatureNotAvailableException(
                'Twilio client must be set by calling ' . __CLASS__ . '::setTwilioClient().'
            );
        }

        try {
            $this->twilioClient->calls($callId)->update(
                [
                    'method' => 'POST',
                    'url' => $redirectUrl
                ]
            );
        } catch (TwilioException $e) {
            throw new DriverInternalException('An error has occurred: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function createOutgoingCall($endpointUrl, $eventUrl, $fromNumber, $toNumber, $ringingTimeout)
    {
        if ($this->twilioClient === null) {
            throw new FeatureNotAvailableException(
                'Twilio client must be set by calling ' . __CLASS__ . '::setTwilioClient().'
            );
        }

        try {
            $this->twilioClient->calls->create(
                '+' . $toNumber, // to
                '+' . $fromNumber, // from
                [
                    'url' => $endpointUrl,
                    'method' => 'POST',
                    'StatusCallback' => $eventUrl,
                    'StatusCallbackMethod' => 'POST',
                    'timeout' => $ringingTimeout,
                ]
            );
        } catch (TwilioException $e) {
            throw new DriverInternalException('An error has occurred: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * @param string $url
     * @throws SecurityViolationException
     */
    private function checkIfRequestIsGenuine($url)
    {
        if ($this->authToken !== null) {
            if (!isset($_SERVER['HTTP_X_TWILIO_SIGNATURE'])) {
                throw new SecurityViolationException('This request may not be genuine (signature not sent)');
            }

            $signatureValidator = new TwilioSignatureValidator();
            if (!$signatureValidator->isSignatureValid(
                $_SERVER['HTTP_X_TWILIO_SIGNATURE'],
                $url,
                $this->authToken,
                $_POST
            )) {
                throw new SecurityViolationException('This request may not be genuine (invalid signature)');
            }
        }
    }
}
