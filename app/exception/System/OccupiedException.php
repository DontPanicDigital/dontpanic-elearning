<?php

namespace DontPanic\Exception\System;

use DontPanic\Exception\DontPanicException;

class OccupiedException extends DontPanicException
{

    public function __construct($message = null, $code = 0)
    {
        parent::__construct("{$message} occupied", $code);
    }
}