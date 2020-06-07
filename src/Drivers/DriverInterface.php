<?php

namespace KattSoftware\Telephonify\Drivers;

use KattSoftware\Telephonify\Drivers\Events\EventsProcessorInterface;
use KattSoftware\Telephonify\Drivers\Exceptions\FeatureNotAvailableException;
use KattSoftware\Telephonify\Drivers\Response\AnswerResponseBuilderInterface;
use KattSoftware\Telephonify\Drivers\Exceptions\DriverInternalException;
use KattSoftware\Telephonify\Exceptions\SecurityViolationException;
use KattSoftware\Telephonify\Request;
use UnexpectedValueException;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
interface DriverInterface
{
    /**
     * Creates a KattSoftware\Telephonify\Request instance for an answer request,
     * filling in all possible values from the instance.
     *
     * @param string $url Endpoint full URL
     * @return Request
     * @throws UnexpectedValueException If the current HTTP request is not a valid one
     * @throws SecurityViolationException If the current HTTP request is not genuine and could be one spoofed
     */
    public function createRequest($url);

    /**
     * Creates the driver's AnswerResponseBuilderInterface instance, used for computing the output of an answer request.
     *
     * @return AnswerResponseBuilderInterface
     */
    public function createAnswerResponseBuilder();

    /**
     * Creates the driver's own EventsProcessorInterface, usd in Event requests processing.
     *
     * @param string $url Absolute URL for event processing
     * @return EventsProcessorInterface
     * @throws SecurityViolationException If the current HTTP request is not genuine and could be one spoofed
     */
    public function createEventsProcessor($url);

    /**
     * Redirect a call (by its ID) from the current state to another, via requesting $redirectUrl.
     *
     * @param string $callId
     * @param string $redirectUrl Absolute URL endpoint
     * @throws FeatureNotAvailableException If the driver is not configured to perfom external API calls
     * @throws DriverInternalException If the driver encountered and error while performing an external API call
     */
    public function redirectCall($callId, $redirectUrl);

    /**
     * Create a new, outgoing call.
     *
     * @param string $endpointUrl Absolute URL for the answer endpoint
     * @param string $eventUrl Absolute URL for event processing
     * @param string $fromNumber The number who is calling (must be one of user's numbers)
     * @param string $toNumber The number to be called
     * @param int $ringingTimeout Ringing timeout, provided in seconds
     * @throws FeatureNotAvailableException If the driver is not configured to perform external API calls
     * @throws DriverInternalException If the driver encountered and error while performing an external API call
     */
    public function createOutgoingCall($endpointUrl, $eventUrl, $fromNumber, $toNumber, $ringingTimeout);
}
