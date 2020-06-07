<?php

namespace KattSoftware\Telephonify\Drivers\Events;

use KattSoftware\Telephonify\EventsManager;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
class NexmoEventsProcessor implements EventsProcessorInterface
{
    /** @var array */
    private $requestParameters;

    public function __construct(array $requestParameters)
    {
        $this->requestParameters = $requestParameters;
    }

    /**
     * @param EventsManager $eventsManager
     */
    public function process(EventsManager $eventsManager)
    {
        if (isset($this->requestParameters['type'], $this->requestParameters['body']['duration']) &&
            $this->requestParameters['type'] === 'sip:status'
        ) {
            $eventsManager->fireCallFinished(
                $this->requestParameters['conversation_id'],
                (int)$this->requestParameters['body']['duration']
            );

            return;
        }

        $eventsManager->fireUnknown($this->requestParameters);
    }
}
