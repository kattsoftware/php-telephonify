<?php

namespace KattSoftware\Telephonify\Actions;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
class Redirect
{
    /** @var string */
    private $controllerClass;

    /** @var string */
    private $applicationUrl;

    /**
     * Redirect constructor.
     * @param string $controllerClass
     */
    public function __construct($controllerClass)
    {
        $this->controllerClass = $controllerClass;
    }

    /**
     * @return string
     */
    public function getControllerClass()
    {
        return $this->controllerClass;
    }

    /**
     * @return string
     */
    public function getApplicationUrl()
    {
        return $this->applicationUrl;
    }

    /**
     * @param string $applicationUrl
     */
    public function setApplicationUrl($applicationUrl)
    {
        $this->applicationUrl = $applicationUrl;
    }
}
