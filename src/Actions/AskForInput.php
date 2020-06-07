<?php

namespace KattSoftware\Telephonify\Actions;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
class AskForInput
{
    /** @var string|string[] */
    private $returnTo;
    /** @var int */
    private $maxDigits;
    /** @var int */
    private $timeOut;
    /** @var bool */
    private $endOnHashKey;

    /**
     * AskForInput constructor.
     * @param string|string[] $returnTo
     * @param int $maxDigits
     * @param int $timeOut
     * @param bool $endOnHashKey
     */
    public function __construct($returnTo, $maxDigits, $timeOut, $endOnHashKey)
    {
        $this->returnTo = $returnTo;
        $this->maxDigits = $maxDigits;
        $this->timeOut = $timeOut;
        $this->endOnHashKey = $endOnHashKey;
    }

    /**
     * @return string|string[]
     */
    public function getReturnTo()
    {
        return $this->returnTo;
    }

    /**
     * @return int
     */
    public function getMaxDigits()
    {
        return $this->maxDigits;
    }

    /**
     * @return int
     */
    public function getTimeOut()
    {
        return $this->timeOut;
    }

    /**
     * @return bool
     */
    public function shouldEndOnHashKey()
    {
        return $this->endOnHashKey;
    }
}
