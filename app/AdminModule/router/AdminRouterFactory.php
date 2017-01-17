<?php

namespace AdminModule;

use AdminModule\AclModule\AclRouter;
use AdminModule\CompanyModule\CompanyRouterFactory;
use AdminModule\UserModule\UserRouter;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class AdminRouterFactory implements \IRouter
{

    /**
     * @return RouteList
     */
    public static function createRoutes(RouteList $router)
    {
        AclRouter::createRoutes($router);
        UserRouter::createRoutes($router);
        CompanyRouterFactory::createRoutes($router);

        $router[] = new Route('admin/<presenter>/<action>[/<token>]', [
            'module'    => 'Admin',
            'presenter' => 'Page',
            'action'    => 'default',
        ]);

        return $router;
    }
}