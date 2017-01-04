<?php

namespace AppModule\Exception;

use Exception;
use Nette\Http\IResponse;

/**
 * HTTP 405 Not Found
 */
class Http405MethodNotAllowedException extends HttpException
{

    /**
     * @param string         $message
     * @param Exception|null $previous
     */
    public function __construct($message = '', Exception $previous = null)
    {
        parent::__construct(null, $message, IResponse::S405_METHOD_NOT_ALLOWED, $previous);
    }
}
