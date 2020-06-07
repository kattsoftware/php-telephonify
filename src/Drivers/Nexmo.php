<?php

namespace KattSoftware\Telephonify\Drivers;

use KattSoftware\Telephonify\Drivers\Events\NexmoEventsProcessor;
use KattSoftware\Telephonify\Drivers\Exceptions\DriverInternalException;
use KattSoftware\Telephonify\Exceptions\SecurityViolationException;
use Nexmo\Client;
use KattSoftware\Telephonify\Drivers\Exceptions\FeatureNotAvailableException;
use KattSoftware\Telephonify\Drivers\Response\NexmoResponseBuilder;
use KattSoftware\Telephonify\Request;
use Nexmo\Client\Exception\Exception as NexmoException;
use UnexpectedValueException;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
class Nexmo implements DriverInterface
{
    /** @var null|Client */
    private $nexmoClient;

    private $enforceCallLookUp = false;

    /**
     * @param Client $nexmoClient
     */
    public function setNexmoClient(Client $nexmoClient)
    {
        $this->nexmoClient = $nexmoClient;
    }

    /**
     * @throws FeatureNotAvailableException if the Nexmo API Client is not set
     */
    public function enforceCallLookUpSecurity()
    {
        if ($this->nexmoClient === null) {
            throw new FeatureNotAvailableException(
                'Unable to enable call look up: Nexmo client must be set by calling ' . __CLASS__ . '::setNexmoClient().'
            );
        }

        $this->enforceCallLookUp = true;
    }

    /**
     * @inheritDoc
     */
    public function createRequest($url)
    {
        $requestParameters = $this->getRequestParameters();

        if ($this->enforceCallLookUp) {
            $this->checkIfRequestGenuine($requestParameters);
        }

        return new Request(
            $requestParameters['conversation_uuid'],
            isset($requestParameters['from']) && $requestParameters['from'] !== 'anonymous' ? $requestParameters['from'] : null,
            isset($requestParameters['to']) ? $requestParameters['to'] : null,
            isset($requestParameters['dtmf']) ? $requestParameters['dtmf'] : null,
            isset($requestParameters['timed_out']) ? $requestParameters['timed_out'] : null
        );
    }

    /**
     * @inheritDoc
     */
    public function createAnswerResponseBuilder()
    {
        return new NexmoResponseBuilder();
    }

    /**
     * @inheritDoc
     */
    public function createEventsProcessor($url)
    {
        $requestParameters = $this->getRequestParameters();

        if ($this->enforceCallLookUp) {
            $this->checkIfRequestGenuine($requestParameters);
        }

        return new NexmoEventsProcessor($requestParameters);
    }

    /**
     * @inheritDoc
     */
    public function redirectCall($callId, $redirectUrl)
    {
        if ($this->nexmoClient === null) {
            throw new FeatureNotAvailableException(
                'Nexmo client must be set by calling ' . __CLASS__ . '::setNexmoClient().'
            );
        }

        try {
            $this->nexmoClient->calls()->put(
                [
                    'action' => 'transfer',
                    'destination' => [
                        'type' => 'ncco',
                        'url' => [$redirectUrl]
                    ]
                ],
                $callId
            );
        } catch (NexmoException $e) {
            throw new DriverInternalException('An error has occurred: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * @return array
     * @throws UnexpectedValueException
     */
    private function getRequestParameters()
    {
        if (!isset($_SERVER['REQUEST_METHOD']) || strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
            throw new UnexpectedValueException('The request HTTP method is not POST');
        }

        $bodyPayload = file_get_contents('php://input');

        $decodedBodyPayload = @json_decode($bodyPayload, true);

        if (!isset($decodedBodyPayload['conversation_uuid'])) {
            throw new UnexpectedValueException('The HTTP request payload is invalid. Nexmo parameter "uuid" is not available.');
        }

        if (isset($decodedBodyPayload['payload']['from'], $decodedBodyPayload['payload']['to'])) {
            $decodedBodyPayload['from'] = $decodedBodyPayload['payload']['from'];
            $decodedBodyPayload['to'] = $decodedBodyPayload['payload']['to'];
        }

        return $decodedBodyPayload;
    }

    /**
     * @inheritDoc
     */
    public function createOutgoingCall($endpointUrl, $eventUrl, $fromNumber, $toNumber, $ringingTimeout)
    {
        if ($this->nexmoClient === null) {
            throw new FeatureNotAvailableException(
                'Nexmo client must be set by calling ' . __CLASS__ . '::setNexmoClient().'
            );
        }

        try {
            $this->nexmoClient->calls()->post(
                [
                    'to' => [[
                        'type' => 'phone',
                        'number' => $toNumber,
                    ]],
                    'from' => [
                        'type' => 'phone',
                        'number' => $fromNumber,
                    ],
                    'answer_url' => [$endpointUrl],
                    'answer_method' => 'POST',
                    'event_url' => [$eventUrl],
                    'event_method' => 'POST',
                    'ringing_timer' => $ringingTimeout,
                ]
            );
        } catch (NexmoException $e) {
            throw new DriverInternalException('An error has occurred: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * @param string[] $requestParameters
     * @throws SecurityViolationException
     */
    private function checkIfRequestGenuine(array $requestParameters)
    {
        try {
            $call = $this->nexmoClient->calls()->get($requestParameters['uuid']);
        } catch (NexmoException $e) {
            throw new SecurityViolationException('This request may not be genuine', 0, $e);
        }

        if (isset($requestParameters['to']) && $call->getTo()->getNumber() !== $requestParameters['to']) {
            throw new SecurityViolationException('This request may not be genuine');
        }

        if (isset($requestParameters['from']) && $call->getFrom()->getNumber() !== $requestParameters['from']) {
            throw new SecurityViolationException('This request may not be genuine');
        }
    }
}
