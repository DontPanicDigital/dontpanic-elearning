<?php

namespace DontPanic\Exception\System;

use DontPanic\Exception\DontPanicException;

class MalformedException extends DontPanicException
{

    public function __construct($message = null, $code = 0)
    {
        parent::__construct("Malformed {$message}", $code);
    }
}