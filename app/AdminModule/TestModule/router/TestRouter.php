<?php

namespace AdminModule\TestModule;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class TestRouterFactory implements \IRouter
{

    /**
     * @param RouteList $router
     *
     * @return array|RouteList
     */
    public static function createRoutes(RouteList $router)
    {
        // DEFAULT

        $router[] = new Route('admin/test/<presenter>[/<action>[/<id>]]', [
            'module'    => 'Admin:Test',
            'presenter' => 'Page',
            'action'    => 'default',
        ]);

        return $router;
    }
}