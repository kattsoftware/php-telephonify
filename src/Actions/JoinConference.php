<?php

namespace KattSoftware\Telephonify\Actions;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
class JoinConference
{
    /** @var string */
    private $name;

    /** @var bool */
    private $startWhenEntering;

    /** @var bool */
    private $endWhenLeaving;

    /** @var bool */
    private $muted;

    /** @var string|null */
    private $waitingMusicUrl;

    /**
     * JoinWaitingRoom constructor.
     * @param string $name
     * @param bool $startWhenEntering
     * @param bool $endWhenLeaving
     * @param bool $muted
     * @param string|null $waitingMusicUrl
     */
    public function __construct($name, $startWhenEntering, $endWhenLeaving, $muted, $waitingMusicUrl)
    {
        $this->name = $name;
        $this->startWhenEntering = $startWhenEntering;
        $this->endWhenLeaving = $endWhenLeaving;
        $this->muted = $muted;
        $this->waitingMusicUrl = $waitingMusicUrl;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function shouldStartWhenEntering()
    {
        return $this->startWhenEntering;
    }

    /**
     * @return bool
     */
    public function shouldEndWhenLeaving()
    {
        return $this->endWhenLeaving;
    }

    /**
     * @return bool
     */
    public function isMuted()
    {
        return $this->muted;
    }

    /**
     * @return string|null
     */
    public function getWaitingMusicUrl()
    {
        return $this->waitingMusicUrl;
    }
}
