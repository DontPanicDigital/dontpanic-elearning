<?php

namespace zvitek\Application\Responses;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nette;
use Nette\Application\Responses\JsonResponse as BaseResponse;

class JsonResponse extends BaseResponse
{

    /** @var SerializerInterface */
    protected $serializer;

    /** @var SerializationContext */
    protected $context;

    /**
     * @param SerializerInterface  $serializer
     * @param SerializationContext $context
     * @param array|\stdClass      $payload
     * @param string               $contentType
     */
    public function __construct(SerializerInterface $serializer, SerializationContext $context, $payload, $contentType = null)
    {
        parent::__construct($payload, $contentType);

        $this->serializer = $serializer;
        $this->context    = $context;
    }

    /**
     * @inheritdoc
     */
    public function send(Nette\Http\IRequest $httpRequest, Nette\Http\IResponse $httpResponse)
    {
        $httpResponse->setContentType($this->contentType);
        $httpResponse->setExpiration(false);

        echo $this->serializer->serialize($this->payload, 'json', $this->context);
    }

}