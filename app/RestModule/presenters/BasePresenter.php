<?php

namespace RestModule;

use DontPanic\Auth\APITokenAuthenticator;
use DontPanic\ParametersProvider;
use Nette;
use App\Model;
use RestModule\Exception\Http400BadRequestException;
use zvitek\Application\ResponseFactoryInterface;
use Nette\Utils\Json;

abstract class BasePresenter extends \App\Presenters\BasePresenter
{

    const CONTENTTYPE_JSON = 'application/json';

    /** @var ResponseFactoryInterface @inject */
    public $responseFactory;

    /** @var APITokenAuthenticator @inject */
    public $tokenAuthenticator;

    /** @var array */
    protected $body;

    protected function startup()
    {
        parent::startup();
        $this->getUser()->setExpiration(0, true, true);
        $this->initRequestBody();
    }

    protected function secured($admin = false)
    {
        try {
            $tokenStr = $this->getHttpRequest()->getHeader('Authorization');

            if (!$tokenStr) {
                die('invalid token');
            }

            $tokenStr = preg_replace('/^Bearer (.+)/i', '$1', trim($tokenStr));
            $identity = $this->tokenAuthenticator->authenticate([ $tokenStr ]);

            $this->getUser()->login($identity);
            $this->userEntity = $this->userModel->findById($this->getUser()->id, true);

            if (!$this->userEntity) {
                throw new Http400BadRequestException;
            }
        } catch (\Exception $e) {
            die('not user');
        }
    }

    private function initRequestBody()
    {
        $request = parent::getHttpRequest();
        if ($request->getRawBody()) {
            if ($request->getHeader('Content-Type')) {
                $explodedHeader = explode(';', $request->getHeader('Content-Type'));
                if (in_array(self::CONTENTTYPE_JSON, $explodedHeader, true)) {
                    $this->body = Json::decode($request->getRawBody(), Json::FORCE_ARRAY);
                }
            }
        }
        if (!$this->body) {
            $this->body = [];
        }
    }

    /**
     * @param mixed $data
     *
     * @throws \Nette\Application\AbortException
     */
    protected function sendDataAsResponse($data, $contextStr = 'apiList')
    {
        $context  = $this->parametersProvider->getSerializerContext($contextStr);
        $response = $this->responseFactory->create($data, [ $context ]);
        $this->sendResponse($response);
    }
}
