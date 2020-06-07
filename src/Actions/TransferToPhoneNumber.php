<?php

namespace KattSoftware\Telephonify\Actions;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
class TransferToPhoneNumber
{
    /** @var string */
    private $toPhone;

    /** @var int */
    private $ringingTimeout;

    /** @var string */
    private $fromPhone;

    /**
     * TransferToPhoneNumber constructor.
     * @param string $toPhone
     * @param int $ringingTimeout
     * @param string $fromPhone
     */
    public function __construct($toPhone, $ringingTimeout, $fromPhone)
    {
        $this->toPhone = $toPhone;
        $this->ringingTimeout = $ringingTimeout;
        $this->fromPhone = $fromPhone;
    }

    /**
     * @return string
     */
    public function getToPhone()
    {
        return $this->toPhone;
    }

    /**
     * @return int
     */
    public function getRingingTimeout()
    {
        return $this->ringingTimeout;
    }

    /**
     * @return string
     */
    public function getFromPhone()
    {
        return $this->fromPhone;
    }
}
