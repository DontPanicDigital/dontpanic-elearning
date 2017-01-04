<?php

namespace AppModule\Exception;

use Exception;
use Nette\Http\IResponse;

/**
 * HTTP 401 Unauthorized
 */
class Http401UnauthorizedException extends HttpException
{

    /**
     * @param string         $message
     * @param Exception|null $previous
     */
    public function __construct($message = '', Exception $previous = null)
    {
        parent::__construct(null, $message, IResponse::S401_UNAUTHORIZED, $previous);
    }
}