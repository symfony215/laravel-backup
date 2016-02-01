<?php

namespace Spatie\Backup\Exceptions;

use Exception;

class InvalidCommand extends Exception
{
    public static function create(string $reason) : InvalidCommand
    {
        return new static($reason);
    }
}
