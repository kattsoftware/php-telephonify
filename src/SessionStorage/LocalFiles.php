<?php

namespace KattSoftware\Telephonify\SessionStorage;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
class LocalFiles implements SessionStorageInterface
{
    /** @var string */
    private $path;

    /**
     * LocalFiles constructor.
     * @param string $path Full path to a dir where the sessions could be stored locally
     */
    public function __construct($path)
    {
        $this->path = rtrim($path, '/\\') . DIRECTORY_SEPARATOR;
    }

    /**
     * @inheritDoc
     */
    public function readState($sessionId)
    {
        if (file_exists($this->path . $sessionId)) {
            $data = file_get_contents($this->path . $sessionId);

            if ($data === false) {
                throw new SessionStorageException('Could not read the session data for ID = ' . $sessionId);
            }

            return $data;
        }

        throw new SessionStorageException('No such session exists, with ID = ' . $sessionId);
    }

    /**
     * @inheritDoc
     */
    public function writeState($sessionId, $data)
    {
        $result = file_put_contents($this->path . $sessionId, $data);

        if ($result === false) {
            throw new SessionStorageException('The session could no be written to the file, session ID = ' . $sessionId);
        }
    }

    /**
     * @inheritDoc
     */
    public function deleteState($sessionId)
    {
        if (file_exists($this->path . $sessionId)) {
            unlink($this->path . $sessionId);
        }
    }
}
