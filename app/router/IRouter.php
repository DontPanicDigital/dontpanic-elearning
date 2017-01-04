<?php

use Nette\Application\Routers\RouteList;

interface IRouter
{

    /**
     * @param RouteList $router
     */
    public static function createRoutes(RouteList $router);
}