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

        $router[] = new Route('test-dokoncen/<token [a-zA-Z0-9]{30}>', [
            'module'    => 'Web',
            'presenter' => 'Test',
            'action'    => 'done',
        ]);

        $router[] = new Route('test-absolvovan/<token [a-zA-Z0-9]{30}>', [
            'module'    => 'Web',
            'presenter' => 'Test',
            'action'    => 'completed',
        ]);

        $router[] = new Route('autorizacni-kod/<token [a-zA-Z0-9]{30}>', [
            'module'    => 'Web',
            'presenter' => 'Sign',
            'action'    => 'authCode',
        ]);

        $router[] = new Route('prihlaseni/<token [a-zA-Z0-9]{30}>', [
            'module'    => 'Web',
            'presenter' => 'Sign',
            'action'    => 'testIn',
        ]);

        $router[] = new Route('<presenter>[/<action>[/<id>]]', [
            'module'    => 'Web',
            'presenter' => 'Page',
            'action'    => 'default',
        ]);

        return $router;
    }
}