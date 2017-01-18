<?php

namespace AdminModule;

use AdminModule\AclModule\AclRouter;
use AdminModule\CompanyModule\CompanyRouterFactory;
use AdminModule\TestModule\TestRouterFactory;
use AdminModule\UserModule\UserRouter;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class AdminRouterFactory implements \IRouter
{

    /**
     * @param RouteList $router
     *
     * @return array|RouteList
     */
    public static function createRoutes(RouteList $router)
    {
        AclRouter::createRoutes($router);
        UserRouter::createRoutes($router);
        CompanyRouterFactory::createRoutes($router);
        TestRouterFactory::createRoutes($router);

        $router[] = new Route('admin/<presenter>/<action>[/<token>]', [
            'module'    => 'Admin',
            'presenter' => 'Page',
            'action'    => 'default',
        ]);

        return $router;
    }
}