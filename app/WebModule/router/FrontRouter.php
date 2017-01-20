<?php

namespace WebModule;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class WebRouter implements \IRouter
{

    public static function createRoutes(RouteList $router)
    {
        // DEFAULT

        $router[] = new Route('test/<token [a-zA-Z0-9]{30}>', [
            'module'    => 'Web',
            'presenter' => 'Test',
            'action'    => 'default',
        ]);

        $router[] = new Route('<presenter>[/<action>[/<id>]]', [
            'module'    => 'Web',
            'presenter' => 'Page',
            'action'    => 'default',
        ]);

        return $router;
    }
}