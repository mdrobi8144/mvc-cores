<?php

namespace App\RobiMvc\Core;
use App\RobiMvc\Core\Application;
use App\RobiMvc\Core\middlewares\BaseMiddleware;

class Controller
{
    public string $layout = "main-layout";
    protected array $middlewares = [];
    public string $action = '';
    
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }
    
    public function render($view, $params = [])
    {
        return Application::$app->view->renderView($view, $params);
    }

    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}