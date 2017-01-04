<?php

namespace zvitek\Serializer;

use JMS\Serializer\SerializerInterface;

class SerializerFactory
{

    /**
     * @return SerializerInterface
     */
    public static function create()
    {
        return SerializerBuilderCustom::create()->build();
    }
}