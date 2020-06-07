<?php

namespace KattSoftware\Telephonify;

use KattSoftware\Telephonify\Drivers\DriverInterface;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
abstract class IVRController
{
    /** @var DriverInterface */
    private $driverInstance;

    /**
     * @param Request $request
     * @param Response $response
     */
    abstract public function run(Request $request, Response $response);

    /**
     * @param DriverInterface $driver
     */
    public function setDriver(DriverInterface $driver)
    {
        if ($this->driverInstance === null) {
            $this->driverInstance = $driver;
        }
    }

    /**
     * Returns the currently used driver instance.
     *
     * @return DriverInterface
     */
    public function getDriver()
    {
        return $this->driverInstance;
    }
}
