<?php

namespace App\RobiMvc\Core;

class Route
{
    public static function get($path, $callback)
    {
        return Application::$app->router->get($path, $callback);
    }

    public static function post($path, $callback)
    {
        return Application::$app->router->post($path, $callback);
    }
}
