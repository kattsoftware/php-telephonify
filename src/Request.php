<?php

namespace KattSoftware\Telephonify;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
class Request
{
    /** @var string */
    private $callId;

    /** @var string|null */
    private $callerNumber;

    /** @var string */
    private $callingNumber;

    /** @var string|null */
    private $input;

    /** @var bool */
    private $timedOut;

    /**
     * Request constructor.
     * @param string $callId
     * @param string|null $callerNumber
     * @param string $callingNumber
     * @param string|null $input
     * @param bool $timedOut
     */
    public function __construct($callId, $callerNumber, $callingNumber, $input, $timedOut)
    {
        $this->callId = $callId;
        $this->callerNumber = $callerNumber;
        $this->callingNumber = $callingNumber;
        $this->input = $input;
        $this->timedOut = $timedOut;
    }

    /**
     * Returns the current call unique ID, as given by your provider.
     *
     * @return string
     */
    public function getCallId()
    {
        return $this->callId;
    }

    /**
     * Returns the phone number that initiated the call, in the E.164 format.
     * In special cases, such as anonymous caller ID, this will return null.
     *
     * @return string|null
     */
    public function getCallerNumber()
    {
        return $this->callerNumber;
    }

    /**
     * Alias for the getCallerNumber() method.
     *
     * @return string|null
     */
    public function getFrom()
    {
        return $this->callerNumber;
    }

    /**
     * Returns the phone number that answered the call, in the E.164 format.
     *
     * @return string
     */
    public function getCallingNumber()
    {
        return $this->callingNumber;
    }

    /**
     * Alias for the getCallingNumber() method.
     *
     * @return string
     */
    public function getTo()
    {
        return $this->callingNumber;
    }

    /**
     * If the previous request during the current call ended by using Response::askForInput(),
     * then if there is any user input collected, this method will return true, and false otherwise.
     *
     * @return bool
     */
    public function hasInput()
    {
        return $this->input !== null;
    }

    /**
     * Provides the numeric input that was given by the user upon calling `Response::askForInput()`.
     * It will return null if no input was collected at all.
     *
     * @return string|null
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * If the previous request during the current call ended by using Response::askForInput() and
     * if the user stopped providing any input for the timeout period of time, then this method will return true.
     *
     * @return bool
     */
    public function hasTimedOut()
    {
        return $this->timedOut;
    }
}
