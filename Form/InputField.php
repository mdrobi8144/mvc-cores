<?php

namespace App\RobiMvc\Core\Form;

use App\RobiMvc\Core\Model;

class InputField extends BaseField
{
    public const LABEL_OFF = '';
    public const TYPE_TEXT = 'text';
    public const TYPE_NUMBER = 'number';
    public const TYPE_PASSWORD = 'password';
    public const INPUT_PLACEHOLDER = '';

    public string $label;
    public string $type;
    public string $placeholder;

    public function __construct(Model $model, string $attribute)
    {        
        $this->label = self::LABEL_OFF;
        $this->type = self::TYPE_TEXT;
        $this->placeholder = self::INPUT_PLACEHOLDER;
        parent::__construct($model, $attribute);
    }

    public function typePass()
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    public function setLabel(string $label = null)
    {
        if($label){
            $this->label = '<label class="form-label">'.$label.'</label>';
        }else{
            $this->label = '<label class="form-label">'.$this->model->getLabel($this->attribute).'</label>';
        }
        
        return $this;
    }

    public function placeHolder(string $placeholder)
    {
        $this->placeholder = 'placeholder="'.$placeholder.'"';
        return $this;
    }

    public function renderInput(): string
    {
        return sprintf('<input type="%s" name="%s" value="%s" class="form-control %s" %s>',
            $this->type,
            $this->attribute,
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? 'is-invalid' : '',
            $this->placeholder,
        );
    }
}