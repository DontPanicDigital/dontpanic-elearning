<?php

namespace AppModule\Exception;

use Exception;
use Nette\Http\IResponse;

/**
 * HTTP 400 Bad Request
 */
class Http400BadRequestException extends HttpException
{

    /**
     * @param string         $identifier
     * @param string         $message
     * @param Exception|null $previous
     */
    public function __construct($identifier = '', $message = '', Exception $previous = null)
    {
        parent::__construct($identifier, $message, IResponse::S400_BAD_REQUEST, $previous);
    }
}