<?php

namespace AppModule\Exception;

use Exception;
use JMS\Serializer\Annotation as Serializer;
use Nette\Application\BadRequestException;

/**
 * Base HTTP Exception
 */
class HttpException extends BadRequestException
{

    /**
     * @var string
     *
     * @Serializer\Groups({ "apiV1_detail" })
     * @Serializer\SerializedName("code")
     */
    protected $identifier;

    /**
     * @param string    $identifier
     * @param string    $message
     * @param int       $code
     * @param Exception $previous
     */
    public function __construct($identifier = null, $message = '', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return string
     *
     * @Serializer\Groups({ "apiV1_detail" })
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("message")
     */
    public function getMessageForSerializer()
    {
        $message = $this->getMessage();

        return $message !== '' ? $message : null;
    }
}


