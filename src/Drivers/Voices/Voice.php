<?php

namespace KattSoftware\Telephonify\Drivers\Voices;

use BadMethodCallException;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
abstract class Voice
{
    /**
     * To be completed in the extended classes
     */
    const PROVIDER_VOICES = [];

    /**
     * @var string Instance voice name
     */
    private $voice;

    /**
     * Voice constructor.
     * @param string $voice
     */
    protected function __construct($voice)
    {
        $this->voice = $voice;
    }

    public static function __callStatic($name, $arguments)
    {
        $voices = static::PROVIDER_VOICES;
        if (in_array($name, $voices, true)) {
            return new static($name);
        }

        throw new BadMethodCallException("No voice with name '{$name}' is available as a " . static::class);
    }

    /**
     * @return string The voice name, as it is declared on the static::PROVIDER_VOICES array.
     */
    public function getVoiceName()
    {
        return $this->voice;
    }
}
