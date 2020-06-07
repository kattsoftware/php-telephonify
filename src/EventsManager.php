<?php

namespace KattSoftware\Telephonify;

use KattSoftware\Telephonify\Exceptions\TelephonifyException;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
class EventsManager
{
    const EVENT_UNKNOWN = 'UNKNOWN';
    const EVENT_CALL_FINISHED = 'CALL_FINISHED';

    /** @var callable[] */
    private $callbacks = [];

    /**
     * @param string $eventName
     * @param callable $callback
     * @throws TelephonifyException
     */
    public function registerEventCallback($eventName, callable $callback)
    {
        $event = @constant(__CLASS__ . '::EVENT_' . $eventName);

        if ($event === null) {
            throw new TelephonifyException('The event "' . $eventName . '" is not valid.');
        }

        if (!isset($this->callbacks[$event])) {
            $this->callbacks[$event] = [];
        }

        $this->callbacks[$event][] = $callback;
    }

    /**
     * @param string $callId
     * @param int $duration Call duration, in seconds
     */
    public function fireCallFinished($callId, $duration)
    {
        $this->fireEvent(self::EVENT_CALL_FINISHED, $callId, $duration);
    }

    /**
     * @param array $request
     */
    public function fireUnknown(array $request)
    {
        $this->fireEvent(self::EVENT_UNKNOWN, $request);
    }
    
    /**
     * Internally fire all event's callbacks.
     *
     * @param string $eventName
     * @param array $arguments
     */
    private function fireEvent($eventName, ...$arguments)
    {
        if (isset($this->callbacks[$eventName])) {
            foreach ($this->callbacks[$eventName] as $callback) {
                call_user_func_array($callback, $arguments);
            }
        }
    }
}
