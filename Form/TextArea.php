<?php

namespace App\RobiMvc\Core\Form;

use App\RobiMvc\Core\Model;

class TextArea extends BaseField
{
    public const LABEL_OFF = '';
    public const INPUT_PLACEHOLDER = '';

    public string $label;
    public string $placeholder;

    public function __construct(Model $model, string $attribute)
    {        
        $this->label = self::LABEL_OFF;
        $this->placeholder = self::INPUT_PLACEHOLDER;
        parent::__construct($model, $attribute);
    }

    public function renderInput(): string
    {
        return sprintf('<textarea name="%s" class="form-control %s" rows="4" %s>%s</textarea>',
            $this->attribute,
            $this->model->hasError($this->attribute) ? 'is-invalid' : '',
            $this->placeholder,
            $this->model->{$this->attribute},
        );
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
}
