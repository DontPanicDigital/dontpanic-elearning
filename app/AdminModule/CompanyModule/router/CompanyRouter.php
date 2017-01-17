<?php

namespace AdminModule\CompanyModule;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class CompanyRouterFactory implements \IRouter
{

    /**
     * @param RouteList $router
     *
     * @return array|RouteList
     */
    public static function createRoutes(RouteList $router)
    {
        // DEFAULT

        $router[] = new Route('admin/company/<presenter>[/<action>[/<id>]]', [
            'module'    => 'Admin:Company',
            'presenter' => 'Page',
            'action'    => 'default',
        ]);

        return $router;
    }
}