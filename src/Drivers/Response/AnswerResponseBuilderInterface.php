<?php

namespace KattSoftware\Telephonify\Drivers\Response;

use KattSoftware\Telephonify\Drivers\Voices\Voice;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
interface AnswerResponseBuilderInterface
{
    /**
     * Compute the response based on all methods that were called until now.
     *
     * @return string The driver-compatible API response
     */
    public function asString();

    /**
     * Return the pair of HTTP response headers required by the driver.
     *
     * @return string[]
     */
    public function getResponseHeaders();

    /**
     * Add to the response a read text action.
     *
     * @param string $text Text to be read
     * @param Voice $voice Voice instance
     * @param bool $async If the user can give an input while speaking the text
     * @param int $loop How many times the text to be read (0 = loop forever)
     */
    public function sayText($text, Voice $voice, $async, $loop);

    /**
     * Add to the response a play audio action.
     *
     * @param string $audioUrl The absolute URL to the audio file to be played
     * @param bool $async If the user can give an input while playing the file
     * @param int $loop How many times the file to be played (0 = loop forever)
     */
    public function playAudio($audioUrl, $async, $loop);

    /**
     * Add to the response a redirect to Telephonify's endpoint.
     *
     * @param string $appUrl Returning endpoint URL
     * @param string $callId Call ID, given by the driver
     * @param string $toPhone Additional data: called number
     * @param string $fromPhone Additional data: calling number
     */
    public function redirect($appUrl, $callId, $toPhone, $fromPhone);

    /**
     * Add to the response an action to call for user input.
     *
     * @param string $appUrl Returning endpoint URL
     * @param int $maxDigits Maximum number of digits when to stop the wait for input
     * @param int $timeOut Maximum number of seconds of user idle state before submitting
     * @param bool $endOnHashKey Whether to finish upon user typing '#'
     */
    public function askForInput($appUrl, $maxDigits, $timeOut, $endOnHashKey);

    /**
     * Add to the response a direct transfer to another phone number.
     *
     * @param string $toPhone The phone number to call (redirecting to) for making the transfer
     * @param int $ringingTimeout Timeout in seconds before the call will hangup because of a timeout
     * @param string $fromPhone The phone number which will call the $toPhone number (must be one of your numbers)
     */
    public function transferToPhoneNumber($toPhone, $ringingTimeout, $fromPhone);

    /**
     * Add to the response a conference joining action
     *
     * @param string $name Unique conference name
     * @param bool $startWhenEntering Whether this participant should start the conference (and stop the waiting music, if any)
     * @param bool $endWhenLeaving Whether this call should end when this participant leaves the call
     * @param bool $muted If true, the participant will be able only to hear the conversation, not to speak to it
     * @param string|null $waitingMusicUrl An audio file (absolute URL) to be played while waiting for the conference to be started
     */
    public function joinWaitingRoom($name, $startWhenEntering, $endWhenLeaving, $muted, $waitingMusicUrl);
}
