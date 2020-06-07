<?php

namespace KattSoftware\Telephonify\Drivers\Events;

use KattSoftware\Telephonify\EventsManager;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
interface EventsProcessorInterface
{
    /**
     * @param EventsManager $eventsManager
     */
    public function process(EventsManager $eventsManager);
}
