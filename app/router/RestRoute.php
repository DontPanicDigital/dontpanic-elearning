<?php

use Nette\Application\Routers\Route;

class RestRoute extends Route
{
    const METHOD_POST   = 4;
    const METHOD_GET    = 8;
    const METHOD_PUT    = 16;
    const METHOD_DELETE = 32;
    const RESTFUL       = 64;

    public function match(\Nette\Http\IRequest $httpRequest)
    {
        $httpMethod = $httpRequest->getMethod();

        if (($this->flags & self::RESTFUL) == self::RESTFUL) {
            $presenterRequest = parent::match($httpRequest);
            if ($presenterRequest != null) {
                switch ($httpMethod) {
                    case 'GET':
                        $action = 'default';
                        break;
                    case 'POST':
                        $action = 'create';
                        break;
                    case 'PUT':
                        $action = 'update';
                        break;
                    case 'DELETE':
                        $action = 'delete';
                        break;
                    default:
                        $action = 'default';
                }

                $params           = $presenterRequest->getParams();
                $params['action'] = $action;
                $presenterRequest->setParams($params);

                return $presenterRequest;
            } else {
                return null;
            }
        }

        if (($this->flags & self::METHOD_POST) == self::METHOD_POST
            && $httpMethod != 'POST'
        ) {
            return null;
        }

        if (($this->flags & self::METHOD_GET) == self::METHOD_GET
            && $httpMethod != 'GET'
        ) {
            return null;
        }

        if (($this->flags & self::METHOD_PUT) == self::METHOD_PUT
            && $httpMethod != 'PUT'
        ) {
            return null;
        }

        if (($this->flags & self::METHOD_DELETE) == self::METHOD_DELETE
            && $httpMethod != 'DELETE'
        ) {
            return null;
        }

        return parent::match($httpRequest);
    }
}