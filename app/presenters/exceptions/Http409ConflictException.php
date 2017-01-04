<?php

namespace AppModule\Exception;

use Exception;
use Nette\Http\IResponse;

/**
 * HTTP 409 Conflict
 */
class Http409ConflictException extends HttpException
{

    /**
     * @param string         $message
     * @param Exception|null $previous
     */
    public function __construct($message = '', Exception $previous = null)
    {
        parent::__construct(null, $message, IResponse::S409_CONFLICT, $previous);
    }
}
