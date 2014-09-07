<?php

namespace Pms\Api\Provider\Login;

use Silex\Application;

class LoginBuilder
{
    public static function mountProviderIntoApplication($route, Application $app)
    {
        $app->register(new LoginServiceProvider());
        $app->mount($route, (new LoginControllerProvider())->setBaseRoute($route));
    }
}
