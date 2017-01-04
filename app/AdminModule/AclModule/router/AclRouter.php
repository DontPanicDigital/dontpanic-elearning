<?php

namespace AdminModule\AclModule;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class AclRouter implements \IRouter
{

    /**
     * @param RouteList $router
     *
     * @return array|RouteList
     */
    public static function createRoutes(RouteList $router)
    {
        // DEFAULT

        $router[] = new Route('admin/acl/<presenter>[/<action>[/<id>]]', [
            'module'    => 'Admin:Acl',
            'presenter' => 'Page',
            'action'    => 'default',
        ]);

        return $router;
    }
}