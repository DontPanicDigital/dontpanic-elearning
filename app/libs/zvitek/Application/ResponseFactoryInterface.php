<?php

namespace zvitek\Application;

use Nette\Application\IResponse;

interface ResponseFactoryInterface
{

    /**
     * @param mixed  $payload
     * @param array  $context
     * @param string $contentType
     *
     * @return IResponse
     */
    public function create($payload, array $context, $contentType = null);
}