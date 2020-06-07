<?php

namespace KattSoftware\Telephonify\Result;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
class OngoingCall
{
    const TYPE_INCOMING = 'INCOMING';
    const TYPE_OUTGOING = 'OUTGOING';

    /** @var string */
    private $callId;

    /** @var string */
    private $type;

    /**
     * OngoingCall constructor.
     * @param string $callId
     * @param string $type
     */
    public function __construct($callId, $type)
    {
        $this->callId = $callId;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getCallId()
    {
        return $this->callId;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
