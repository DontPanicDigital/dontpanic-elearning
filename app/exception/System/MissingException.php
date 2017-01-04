<?php

namespace DontPanic\Exception\System;

use DontPanic\Exception\DontPanicException;

class MissingException extends DontPanicException
{

    public function __construct($message = null, $code = 0)
    {
        parent::__construct("Missing {$message}", $code);
    }
}