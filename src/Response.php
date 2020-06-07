<?php

namespace KattSoftware\Telephonify;

use KattSoftware\Telephonify\Actions\AskForInput;
use KattSoftware\Telephonify\Actions\JoinConference;
use KattSoftware\Telephonify\Actions\Play;
use KattSoftware\Telephonify\Actions\Redirect;
use KattSoftware\Telephonify\Actions\SayText;
use KattSoftware\Telephonify\Actions\TransferToPhoneNumber;
use KattSoftware\Telephonify\Drivers\Voices\Voice;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
class Response
{
    /** @var array */
    private $actionsStack = [];

    /**
     * @param string $text
     * @param Voice $voice A Voice instance (based on your provider; see the docs)
     * @param bool $async
     * @param int $loop
     * @return $this
     */
    public function say($text, Voice $voice, $async = false, $loop = 1)
    {
        $this->actionsStack[] = new SayText($text, $voice, $async, $loop);

        return $this;
    }

    public function redirect($controllerClass)
    {
        $this->actionsStack[] = new Redirect($controllerClass);

        return $this;
    }

    public function play($audioUrl, $async = false, $loop = 1)
    {
        $this->actionsStack[] = new Play($audioUrl, $async, $loop);

        return $this;
    }

    public function askForInput($returnTo, $maxDigits = 1, $timeOut = 5, $endOnHashKey = false)
    {
        $this->actionsStack[] = new AskForInput($returnTo, $maxDigits, $timeOut, $endOnHashKey);

        return $this;
    }

    /**
     * @param string $toPhone
     * @param string $fromPhone
     * @param int $ringingTimeout
     * @return $this
     */
    public function transferToPhoneNumber($toPhone, $fromPhone, $ringingTimeout = 10)
    {
        $this->actionsStack[] = new TransferToPhoneNumber($toPhone, $ringingTimeout, $fromPhone);

        return $this;
    }

    /**
     * @param string $uniqueName
     * @param bool $startWhenEntering
     * @param bool $endWhenLeaving
     * @param bool $muted
     * @param string|null $waitingMusicUrl
     * @return $this
     */
    public function joinConference($uniqueName, $startWhenEntering, $endWhenLeaving, $muted, $waitingMusicUrl = null)
    {
        $this->actionsStack[] = new JoinConference($uniqueName, $startWhenEntering, $endWhenLeaving, $muted, $waitingMusicUrl);

        return $this;
    }

    /**
     * @return array
     */
    public function _getActionsStack()
    {
        return $this->actionsStack;
    }
}
