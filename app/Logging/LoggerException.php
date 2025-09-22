<?php

namespace App\Logging;

class LoggerException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}