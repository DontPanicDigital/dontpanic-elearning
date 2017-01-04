<?php

namespace WebModule;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class WebRouter implements \IRouter
{

    public static function createRoutes(RouteList $router)
    {
        // DEFAULT

        $router[] = new Route('<presenter>[/<action>[/<id>]]', [
            'module'    => 'Web',
            'presenter' => 'Page',
            'action'    => 'default',
        ]);

        return $router;
    }
}