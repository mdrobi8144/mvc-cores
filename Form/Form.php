<?php

namespace App\RobiMvc\Core\Form;

use App\RobiMvc\Core\Model;

class Form
{
    public static function begin($action, $method)
    {
        echo sprintf('<form action="%s" method="%s">', $action, $method);
        return new Form();
    }

    public static function end()
    {
        echo '</form>';
    }

    public static function inputField(Model $model, $attribute) //change this method type static or public not effect anywhere
    {
        return new InputField($model, $attribute);
    }

    public static function textArea(Model $model, $attribute)  //change this method type static or public not effect anywhere
    {
        return new TextArea($model, $attribute);
    }

}