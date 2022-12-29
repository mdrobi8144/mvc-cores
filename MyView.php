<?php

namespace App\RobiMvc\Core;

class MyView
{
    public string $title = '';
    public string $layoutN = '';

    public function renderView($view, $params = [])
    {
        $page = $this->renderPage($view, $params);
        $layout = $this->renderLayout();
        return str_replace('{{content}}', $page, $layout);
    }

    // public function renderContent($viewContent)
    // {
    //     $renderLayout = $this->renderLayout();
    //     return str_replace('{{content}}', $viewContent, $renderLayout);
    // }

    protected function renderLayout()
    {
        // $layout = Application::$app->layout;
        // if(Application::$app->controller){
        //     $layout = Application::$app->controller->layout;
        // }
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/$this->layoutN.php";
        return ob_get_clean();
    }

    protected function renderPage($view, $params)
    {
        foreach($params as $key => $value){
            $$key = $value;
        }

        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }

    public function yeld($param)
    {
        echo $param;
    }

    public function setTitle($val)
    {
        $this->title = $val;
    }

    public function getTitle()
    {
        echo $this->title;
    }

    public function setLayout($val)
    {
        $this->layoutN = $val;
    }
}