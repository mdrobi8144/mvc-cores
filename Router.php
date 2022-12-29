<?php

namespace App\RobiMvc\Core;

use App\RobiMvc\Core\exceptions\NotFoundException;

class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        // dd($this->routes);

        $callback = $this->routes[$method][$path] ?? false;
        if($callback === false){
            throw new NotFoundException();
        }

        if(is_string($callback)){
            return Application::$app->view->renderView($callback);
        }else if(is_array($callback)){
            //Default routes
            /*if(is_array($callback)){
                $callback[0] = new $callback[0];
            } or */
            /*Application::$app->controller = new $callback[0]();
            $callback[0] = Application::$app->controller; */

            //for protected routes
            $controller = new $callback[0]();
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] = $controller;

            foreach($controller->getMiddlewares() as $middleware){
                $middleware->execute();
            }
        }

        return call_user_func($callback, $this->request, $this->response);
    }
}