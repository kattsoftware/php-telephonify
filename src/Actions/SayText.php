<?php

namespace KattSoftware\Telephonify\Actions;

use KattSoftware\Telephonify\Drivers\Voices\Voice;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
class SayText
{
    /** @var string */
    private $text;
    /** @var Voice */
    private $voice;
    /** @var bool */
    private $async;
    /** @var int */
    private $loop;

    /**
     * SayText constructor.
     * @param string $text
     * @param Voice $voice
     * @param bool $async
     * @param int $loop
     */
    public function __construct($text, Voice $voice, $async, $loop)
    {
        $this->text = $text;
        $this->voice = $voice;
        $this->async = $async;
        $this->loop = $loop;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return Voice
     */
    public function getVoice()
    {
        return $this->voice;
    }

    /**
     * @return bool
     */
    public function isAsync()
    {
        return $this->async;
    }

    /**
     * @return int|null
     */
    public function getLoop()
    {
        return $this->loop;
    }
}
