<?php

namespace AdminModule\UserModule;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class UserRouter implements \IRouter
{

    /**
     * @param RouteList $router
     *
     * @return array|RouteList
     */
    public static function createRoutes(RouteList $router)
    {
        // DEFAULT

        $router[] = new Route('admin/user/<presenter>[/<id [a-z0-9]{30}>]', [
            'module'    => 'Admin:User',
            'presenter' => 'Page',
            'action'    => 'default',
        ]);

        $router[] = new Route('admin/user/<presenter>[/<action>][/<id>]', [
            'module'    => 'Admin:User',
            'presenter' => 'Page',
            'action'    => 'default',
        ]);

        return $router;
    }
}