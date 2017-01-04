<?php

namespace AppModule\Exception;

use Exception;
use Nette\Http\IResponse;

/**
 * HTTP 403 Unauthorized
 */
class Http403ForbiddenException extends HttpException
{

    /**
     * @param string         $message
     * @param Exception|null $previous
     */
    public function __construct($message = '', Exception $previous = null)
    {
        parent::__construct(null, $message, IResponse::S403_FORBIDDEN, $previous);
    }
}

