<?php

namespace App\Exceptions;

class SeedBoxYesException extends NexusException
{
    public function __construct(private $id = null)
    {
        parent::__construct('SeedBox record exists' . ($id ? ": $id" : ''));
    }

    public function getId()
    {
        return $this->id;
    }
}
