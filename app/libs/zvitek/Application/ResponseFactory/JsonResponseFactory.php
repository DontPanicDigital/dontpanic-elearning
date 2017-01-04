<?php

namespace zvitek\Application\ResponseFactory;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use zvitek\Application\ResponseFactoryInterface;
use zvitek\Application\Responses\JsonResponse;

class JsonResponseFactory implements ResponseFactoryInterface
{

    /** @var SerializerInterface */
    protected $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @inheritdoc
     */
    public function create($payload, array $context, $contentType = null)
    {
        $contextObj = SerializationContext::create()->setSerializeNull(true)->setGroups($context);

        return new JsonResponse($this->serializer, $contextObj, $payload, $contentType);
    }
}