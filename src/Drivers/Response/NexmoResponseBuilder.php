<?php

namespace KattSoftware\Telephonify\Drivers\Response;

use KattSoftware\Telephonify\Drivers\Voices\Voice;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
class NexmoResponseBuilder implements AnswerResponseBuilderInterface
{
    /** @var array The NCCO pieces from the response */
    private $ncco = [];

    /** @var array Async elements (stream & play) actions */
    private $asyncElementsStack = [];

    /**
     * @inheritDoc
     */
    public function asString()
    {
        $this->resetAsyncStack();

        return json_encode($this->ncco);
    }

    /**
     * @inheritDoc
     */
    public function getResponseHeaders()
    {
        return [
            'Content-Type' => 'application/json'
        ];
    }

    /**
     * @inheritDoc
     */
    public function sayText($text, Voice $voice, $async, $loop)
    {
        $voiceNameParts = explode('_', $voice->getVoiceName());
        $voiceName = isset($voiceNameParts[2]) ? ucfirst(strtolower($voiceNameParts[2])) : '';

        $nccoElement = [
            'action' => 'talk',
            'text' => $text,
            'loop' => $loop,
            'voiceName' => $voiceName,
            'bargeIn' => $async
        ];

        $this->prepareAsyncElement($nccoElement);
    }

    /**
     * @inheritDoc
     */
    public function playAudio($audioUrl, $async, $loop)
    {
        $nccoElement = [
            'action' => 'stream',
            'streamUrl' => [$audioUrl],
            'bargeIn' => $async,
            'loop' => $loop
        ];

        $this->prepareAsyncElement($nccoElement);
    }

    /**
     * @inheritDoc
     */
    public function redirect($appUrl, $callId, $toPhone, $fromPhone)
    {
        $this->resetAsyncStack();

        $this->ncco[] = [
            'action' => 'notify',
            'payload' => [
                'conversation_uuid' => $callId,
                'from' => $fromPhone,
                'to' => $toPhone,
            ],
            'eventUrl' => [$appUrl],
            'eventMethod' => 'POST'
        ];
    }

    /**
     * @inheritDoc
     */
    public function askForInput($appUrl, $maxDigits, $timeOut, $endOnHashKey)
    {
        $this->resetAsyncStack(true);

        $this->ncco[] = [
            'action' => 'input',
            'timeOut' => $timeOut,
            'maxDigits' => $maxDigits,
            'submitOnHash' => $endOnHashKey,
            'eventUrl' => [$appUrl],
            'eventMethod' => 'POST'
        ];
    }

    /**
     * @inheritDoc
     */
    public function transferToPhoneNumber($toPhone, $ringingTimeout, $fromPhone)
    {
        $this->resetAsyncStack();

        $entry = [
            'action' => 'connect',
            'timeout' => $ringingTimeout,
            'from' => $fromPhone,
            'endpoint' => [
                [
                    'type' => 'phone',
                    'number' => $toPhone,
                ]
            ]
        ];

        $this->ncco[] = $entry;
    }

    /**
     * @inheritDoc
     */
    public function joinWaitingRoom($name, $startWhenEntering, $endWhenLeaving, $muted, $waitingMusicUrl)
    {
        $this->resetAsyncStack();

        $entry = [
            'action' => 'conversation',
            'name' => $name,
            'startOnEnter' => $startWhenEntering,
            'endOnExit' => $endWhenLeaving,
        ];

        if ($muted) {
            $entry['canSpeak'] = [];
        }

        if ($waitingMusicUrl !== null) {
            $entry['musicOnHoldUrl'] = [$waitingMusicUrl];
        }

        $this->ncco[] = $entry;
    }

    /**
     * @param bool $keepAsync
     */
    private function resetAsyncStack($keepAsync = false)
    {
        if ($this->asyncElementsStack !== []) {
            foreach ($this->asyncElementsStack as $element) {
                if (!$keepAsync) {
                    $element['bargeIn'] = false;
                }

                $this->ncco[] = $element;
            }

            $this->asyncElementsStack = [];
        }
    }

    /**
     * @param array $nccoElement
     */
    private function prepareAsyncElement(array $nccoElement)
    {
        if ($nccoElement['bargeIn']) {
            $this->asyncElementsStack[] = $nccoElement;
        } else {
            if ($this->asyncElementsStack !== []) {
                // Reset the async stack, discarding all async stack elements
                // directly into NCCO output
                $this->resetAsyncStack();
            }

            $this->ncco []= $nccoElement;
        }
    }
}
