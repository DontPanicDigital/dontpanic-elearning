<?php

namespace AppModule\Exception;

use Exception;
use Nette\Http\IResponse;

/**
 * HTTP 500 Internal Server Error
 */
class Http500InternalServerError extends HttpException
{

    /**
     * @param string         $message
     * @param Exception|null $previous
     */
    public function __construct($message = '', \Exception $previous = null)
    {
        parent::__construct(null, $message, IResponse::S500_INTERNAL_SERVER_ERROR, $previous);
    }
}