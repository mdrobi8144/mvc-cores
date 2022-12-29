<?php

namespace App\RobiMvc\Core\Form;
use App\RobiMvc\Core\Model;

class Field
{
    public const LABEL_OFF = '';
    public const TYPE_TEXT = 'text';
    public const TYPE_NUMBER = 'number';
    public const TYPE_PASSWORD = 'password';
    public const INPUT_PLACEHOLDER = '';

    public string $label;
    public string $type;
    public Model $model;
    public string $attribute;
    public string $placeholder;

    public function __construct(\App\RobiMvc\Core\Model $model, string $attribute, $prprty = [])
    {
        
        foreach($prprty as $key => $value){
            $$key = $value;
        }
        // $this->label = $label; //when setLabel() is used then block this line otherwise unblocked this line
        // $this->label = $attribute;
        //if type and label coming with prprty[] then use this "$this->type = $type;" otherwise "$this->type = self::TYPE_TEXT;" 
        
        $this->label = self::LABEL_OFF;
        $this->type = self::TYPE_TEXT;
        $this->placeholder = self::INPUT_PLACEHOLDER;
        $this->model = $model;
        $this->attribute = $attribute;
    }

    public function __toString()
    {
        return sprintf('
            <div class="form-group mb-3">
                %s
                <input type="%s" name="%s" value="%s" class="form-control %s" %s>
                <div class="invalid-feedback">%s</div>
            </div>
        ',  
            $this->label,
            $this->type,
            $this->attribute,
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? 'is-invalid' : '',
            $this->placeholder,
            $this->model->getFirstError($this->attribute)
        );
    }

    public function typePass()
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    public function setLabel(string $label)
    {
        $this->label = '<label class="form-label">'.$label.'</label>';
        return $this;
    }

    public function placeHolder(string $placeholder)
    {
        $this->placeholder = 'placeholder="'.$placeholder.'"';
        return $this;
    }
}