<?php

namespace KattSoftware\Telephonify;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
class CallState implements \JsonSerializable
{
    /** @var string[] List of controllers to continue to (can be on element, or ['1' => IVRController, ..., '*' => IVRController] */
    private $continueTo = [];

    /** @var string Who is being called (E.164 format) */
    private $callingNumber;

    /** @var string Who is calling (E.164 format) */
    private $callerNumber;

    /**
     * Factory method
     *
     * @param string[] $state
     * @return CallState
     */
    public static function createFromState(array $state)
    {
        $instance = new self();

        $instance->setContinueTo($state['continueTo']);
        $instance->setCallerNumber($state['callerNumber']);
        $instance->setCallingNumber($state['callingNumber']);

        return $instance;
    }

    /**
     * @return string[]
     */
    public function getContinueTo()
    {
        return $this->continueTo;
    }

    /**
     * @param string[] $continueTo
     */
    public function setContinueTo(array $continueTo)
    {
        $this->continueTo = $continueTo;
    }

    /**
     * @return string
     */
    public function getCallingNumber()
    {
        return $this->callingNumber;
    }

    /**
     * @param string $callidNumber
     */
    public function setCallingNumber($callingNumber)
    {
        $this->callingNumber = $callingNumber;
    }

    /**
     * @return string
     */
    public function getCallerNumber()
    {
        return $this->callerNumber;
    }

    /**
     * @param string $callerNumber
     */
    public function setCallerNumber($callerNumber)
    {
        $this->callerNumber = $callerNumber;
    }

    /**
     * Specify data which should be serialized to JSON
     * @return array data which can be serialized by <b>json_encode</b>
     */
    public function jsonSerialize()
    {
        return [
            'continueTo' => $this->continueTo,
            'callerNumber' => $this->callerNumber,
            'callingNumber' => $this->callingNumber,
        ];
    }
}
