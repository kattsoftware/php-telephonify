<?php

namespace KattSoftware\Telephonify\Actions;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
class Play
{
    /** @var string */
    private $audioUrl;

    /** @var bool */
    private $async;

    /** @var int */
    private $loop;

    /**
     * Play constructor.
     * @param string $audioUrl
     * @param bool $async
     * @param int $loop
     */
    public function __construct($audioUrl, $async, $loop)
    {
        $this->audioUrl = $audioUrl;
        $this->async = $async;
        $this->loop = $loop;
    }

    /**
     * @return string
     */
    public function getAudioUrl()
    {
        return $this->audioUrl;
    }

    /**
     * @return bool
     */
    public function isAsync()
    {
        return $this->async;
    }

    /**
     * @return int
     */
    public function getLoop()
    {
        return $this->loop;
    }
}
