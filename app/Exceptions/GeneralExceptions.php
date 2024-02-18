<?php

namespace App\Exceptions;

use Exception;

class GeneralExceptions extends Exception
{
    protected $code;

    public function __construct($message, $code, $previous = null)
    {
        $this->code = $code;
        parent::__construct($message, 0, $previous);
    }

    public function getCustomCode()
    {
        return $this->code;
    }
}