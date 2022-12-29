<?php

namespace App\RobiMvc\Core\Form;

use App\RobiMvc\Core\Model;

abstract class BaseField
{
    public Model $model;
    public string $attribute;

    public function __construct(Model $model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    abstract public function renderInput(): string;

    public function __toString()
    {
        return sprintf('
            <div class="form-group mb-3">
                %s
                %s
                <div class="invalid-feedback">%s</div>
            </div>
        ',  
            $this->label,
            $this->renderInput(),
            $this->model->getFirstError($this->attribute)
        );
    }
}
