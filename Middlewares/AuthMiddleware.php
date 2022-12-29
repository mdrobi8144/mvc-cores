<?php

namespace App\RobiMvc\Core\Middlewares;

use App\RobiMvc\Core\Application;
use App\RobiMvc\Core\Exceptions\ForbiddenException;

class AuthMiddleware extends BaseMiddleware
{
    protected array $actions = [];

    public function __construct($actions = [])
    {
        $this->actions = $actions;
    }

    public function execute()
    {
		// dd(Application::$app->controller->action, 'd');
        // var_dump(Application::$app->controller->action);
        if (Application::isGuest()) {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                throw new ForbiddenException();
            }
        }
    }
}