<?php

namespace DontPanic;

use Nette;

class ParametersProvider extends Nette\Object
{

    protected $serializer;

    protected $rest;

    protected $acl;

    /** @var Nette\Http\Request */
    protected $request;

    /**
     * ParametersProvider constructor.
     *
     * @param                    $seralizer
     * @param                    $rest
     * @param                    $acl
     * @param Nette\Http\Request $request
     */
    public function __construct(
        $seralizer,
        $rest,
        $acl,
        Nette\Http\Request $request
    )
    {
        $this->serializer = $seralizer;
        $this->rest       = $rest;
        $this->acl        = $acl;
        $this->request    = $request;
    }

    /**
     * @return bool
     */
    public function isProduction()
    {
        return ENVIRONMENT === 'prod';
    }

    /**
     * @return bool
     */
    public function isDevelopment()
    {
        return ENVIRONMENT === 'dev';
    }

    public function getSerializerContext($context)
    {
        if (isset($this->serializer[$context])) {
            return $this->serializer[$context];
        }

        return null;
    }

    public function getRest()
    {
        return $this->rest;
    }

    public function getAcl()
    {
        return $this->acl;
    }
}
