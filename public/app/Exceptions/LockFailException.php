<?php

namespace App\Exceptions;

class LockFailException extends NexusException
{
    private $name;

    public function __construct(string $name = '', $owner = null)
    {
        $this->name = $name;
        parent::__construct("Can not acquire lock: $name" . ($owner ? ", owner: $owner" : ''));
    }

    public function getName()
    {
        return $this->name;
    }
}
