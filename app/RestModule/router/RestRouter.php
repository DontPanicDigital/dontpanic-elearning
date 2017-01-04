<?php

namespace RestModule;

use Nette\Application\Routers\RouteList;
use RestRoute;

class RestRouter implements \IRouter
{
    const API_V1_PREFIX = 'api/v1/';

    public static function createRoutes(RouteList $router) {

        // USER

        $router[] = new RestRoute(self::API_V1_PREFIX . 'user/detail', [
            'module'    => 'Rest',
            'presenter' => 'User',
            'action'    => 'detail',
        ], RestRoute::METHOD_GET);

        $router[] = new RestRoute(self::API_V1_PREFIX . 'user/update', [
            'module'    => 'Rest',
            'presenter' => 'User',
            'action'    => 'update',
        ], RestRoute::METHOD_PUT);

        // USER LANGUAGE

        $router[] = new RestRoute(self::API_V1_PREFIX . 'user-language/create', [
            'module'    => 'Rest',
            'presenter' => 'UserLanguage',
            'action'    => 'create',
        ], RestRoute::METHOD_POST);

        $router[] = new RestRoute(self::API_V1_PREFIX . 'user-language/option', [
            'module'    => 'Rest',
            'presenter' => 'UserLanguage',
            'action'    => 'option',
        ], RestRoute::METHOD_GET);

        $router[] = new RestRoute(self::API_V1_PREFIX . 'user-language/list', [
            'module'    => 'Rest',
            'presenter' => 'UserLanguage',
            'action'    => 'list',
        ], RestRoute::METHOD_GET);

        $router[] = new RestRoute(self::API_V1_PREFIX . 'user-language/<token [0-9a-zA-Z]{6}>/remove', [
            'module'    => 'Rest',
            'presenter' => 'UserLanguage',
            'action'    => 'remove',
        ], RestRoute::METHOD_GET);

        // CATEGORY

        $router[] = new RestRoute(self::API_V1_PREFIX . 'category/list', [
            'module'    => 'Rest',
            'presenter' => 'Category',
            'action'    => 'list',
        ], RestRoute::METHOD_GET);

        // CITY

        $router[] = new RestRoute(self::API_V1_PREFIX . 'city/list', [
            'module'    => 'Rest',
            'presenter' => 'City',
            'action'    => 'list',
        ], RestRoute::METHOD_GET);

        // REGION

        $router[] = new RestRoute(self::API_V1_PREFIX . 'region/list', [
            'module'    => 'Rest',
            'presenter' => 'Region',
            'action'    => 'list',
        ], RestRoute::METHOD_GET);

        // COUNTRY

        $router[] = new RestRoute(self::API_V1_PREFIX . 'country/list', [
            'module'    => 'Rest',
            'presenter' => 'Country',
            'action'    => 'list',
        ], RestRoute::METHOD_GET);

        // COMPANY

        $router[] = new RestRoute(self::API_V1_PREFIX . 'companyString/<token [0-9a-zA-Z]{20}>/update', [
            'module'    => 'Rest',
            'presenter' => 'Company',
            'action'    => 'update',
        ], RestRoute::METHOD_PUT);

        // WORK EXPERIENCE

        $router[] = new RestRoute(self::API_V1_PREFIX . 'work-experience/create', [
            'module'    => 'Rest',
            'presenter' => 'WorkExperience',
            'action'    => 'create',
        ], RestRoute::METHOD_POST);

        $router[] = new RestRoute(self::API_V1_PREFIX . 'work-experience/<token [0-9a-zA-Z]{6}>/update', [
            'module'    => 'Rest',
            'presenter' => 'WorkExperience',
            'action'    => 'update',
        ], RestRoute::METHOD_PUT);

        $router[] = new RestRoute(self::API_V1_PREFIX . 'work-experience/<token [0-9a-zA-Z]{6}>/remove', [
            'module'    => 'Rest',
            'presenter' => 'WorkExperience',
            'action'    => 'remove',
        ], RestRoute::METHOD_GET);

        $router[] = new RestRoute(self::API_V1_PREFIX . 'work-experience/list', [
            'module'    => 'Rest',
            'presenter' => 'WorkExperience',
            'action'    => 'list',
        ], RestRoute::METHOD_GET);

        // COURSE

        $router[] = new RestRoute(self::API_V1_PREFIX . 'course/create', [
            'module'    => 'Rest',
            'presenter' => 'Course',
            'action'    => 'create',
        ], RestRoute::METHOD_POST);

        $router[] = new RestRoute(self::API_V1_PREFIX . 'course/<token [0-9a-zA-Z]{6}>/update', [
            'module'    => 'Rest',
            'presenter' => 'Course',
            'action'    => 'update',
        ], RestRoute::METHOD_PUT);

        $router[] = new RestRoute(self::API_V1_PREFIX . 'course/<token [0-9a-zA-Z]{6}>/remove', [
            'module'    => 'Rest',
            'presenter' => 'Course',
            'action'    => 'remove',
        ], RestRoute::METHOD_GET);

        $router[] = new RestRoute(self::API_V1_PREFIX . 'course/list', [
            'module'    => 'Rest',
            'presenter' => 'Course',
            'action'    => 'list',
        ], RestRoute::METHOD_GET);

        return $router;
    }
}