<?php

namespace zvitek\Serializer;

use JMS\Serializer\Handler\ArrayCollectionHandler;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Handler\PhpCollectionHandler;
use JMS\Serializer\Handler\PropelCollectionHandler;
use JMS\Serializer\SerializerBuilder;

/**
 * Class SerializerBuilderCustom
 *
 * @package kytart\Serializer
 */
class SerializerBuilderCustom extends SerializerBuilder
{

    public function build()
    {
        $this->configureHandlers(function (HandlerRegistry $handlerRegistry) {
            $handlerRegistry->registerSubscribingHandler(new DateHandlerCustom('Y-m-d\TH:i:s.000\Z'));
            $handlerRegistry->registerSubscribingHandler(new PhpCollectionHandler());
            $handlerRegistry->registerSubscribingHandler(new ArrayCollectionHandler());
            $handlerRegistry->registerSubscribingHandler(new PropelCollectionHandler());
        });

        return parent::build();
    }

}