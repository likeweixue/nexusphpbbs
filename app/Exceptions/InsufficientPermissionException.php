<?php

namespace App\Exceptions;

class InsufficientPermissionException extends NexusException
{
    protected $message = 'Insufficient permission';
}
