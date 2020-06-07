<?php

namespace KattSoftware\Telephonify\Drivers\Events;

use KattSoftware\Telephonify\EventsManager;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
class TwilioEventsProcessor implements EventsProcessorInterface
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
        if (isset($this->requestParameters['CallStatus']) && $this->requestParameters['CallStatus'] === 'completed') {
            $eventsManager->fireCallFinished(
                $this->requestParameters['CallSid'],
                (int)$this->requestParameters['CallDuration']
            );

            return;
        }

        $eventsManager->fireUnknown($this->requestParameters);
    }
}
