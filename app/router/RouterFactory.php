<?php

use AdminModule\AdminRouterFactory;
use WebModule\WebRouter;
use Nette\Application\Routers\RouteList;
use RestModule\RestRouter;

class RouterFactory
{

    /**
     * @return RouteList
     */
    public function createRouter()
    {
        $router = new RouteList();

        AdminRouterFactory::createRoutes($router);
        RestRouter::createRoutes($router);
        WebRouter::createRoutes($router);

        return $router;
    }
}