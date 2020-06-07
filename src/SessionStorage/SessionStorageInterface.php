<?php

namespace KattSoftware\Telephonify\SessionStorage;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
interface SessionStorageInterface
{
    /**
     * Read a session data by its ID.
     *
     * @param string $sessionId
     * @return string
     * @throws SessionStorageException if reading cannot be performed
     */
    public function readState($sessionId);

    /**
     * Write a session data, based on its ID.
     * If the session exists, it should be overwritten.
     *
     * @param string $sessionId
     * @param string $data
     * @throws SessionStorageException if writing cannot be performed
     */
    public function writeState($sessionId, $data);

    /**
     * Remove a session based on its ID.
     *
     * @param string $sessionId
     */
    public function deleteState($sessionId);
}
