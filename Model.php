<?php

namespace App\RobiMvc\Core;

abstract class Model
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';
    public const RULE_EXISTS = 'exists';

    public function loadData($data)
    {
        foreach($data as $key => $value){
            if(property_exists($this, $key)){
               $this->{$key} = $value;
            }
        }
    }
    
    abstract public function rules(): array;

    public function labels(): array
    {
        return [];
    }

    public function getLabel($attribute)
    {
        return $this->labels()[$attribute] ?? $attribute;
    }

    public array $errors = [];

    public function validate()
    {
        // dd($this->rules());
        foreach($this->rules() as $attribute => $rules){
            $value = $this->{$attribute};
            foreach($rules as $rule){
                $ruleName = $rule;
                if(!is_string($ruleName)){
                    $ruleName = $rule[0];
                }

                if($ruleName === self::RULE_REQUIRED && !$value){
                    $this->addErrorForRule($attribute, self::RULE_REQUIRED, ['field' => strtolower($this->getLabel($attribute))]);
                }

                if($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)){
                    $this->addErrorForRule($attribute, self::RULE_EMAIL, ['field' => strtolower($this->getLabel($attribute))]);
                }

                if($ruleName === self::RULE_MIN && strlen($value) < $rule['min']){
                    $this->addErrorForRule($attribute, self::RULE_MIN, ['field' => strtolower($this->getLabel($attribute)), 'min' => $this->getLabel($rule['min'])]);
                }

                if($ruleName === self::RULE_MAX && strlen($value) > $rule['max']){
                    $this->addErrorForRule($attribute, self::RULE_MAX, ['field' => strtolower($this->getLabel($attribute)), 'max' => $this->getLabel($rule['max'])]);
                }

                if($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}){
                    $this->addErrorForRule($attribute, self::RULE_MATCH, ['field' => strtolower($this->getLabel($attribute)), 'asfield' => strtolower($this->getLabel($rule['match']))]);
                }
                
                if($ruleName === self::RULE_UNIQUE){
                    $className = $rule['class'];
                    $uniqueAttr = $rule['attribute'] ?? $attribute;
                    $tableName = $className::tableName();
                    $statement = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attr");
                    $statement->bindValue(":attr", $value);
                    $statement->execute();
                    $record = $statement->fetchObject();
                    if($record){
                        $this->addErrorForRule($attribute, self::RULE_UNIQUE, ['field' => strtolower($this->getLabel($attribute))]);
                    }
                }

                if($ruleName === self::RULE_EXISTS){
                    // $className = $rule['class'];
                    $existsAttr = $rule['field'];
                    $tableName = $rule['table'];
                    $statement = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $existsAttr = :exattr");
                    $statement->bindValue(":exattr", $value);
                    $statement->execute();
                    $record = $statement->fetchObject();
                    if($record){
                        $this->addErrorForRule($attribute, self::RULE_EXISTS, ['field' => strtolower($this->getLabel($attribute))]);
                    }
                }
            }
        }

        return empty($this->errors);
    }

    private function addErrorForRule(string $attribute, string $rule, $params = [])
    {
        $message = $this->errorMessages()[$rule] ?? '';
        
        foreach($params as $key => $value){
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    public function addError(string $attribute, string $message)
    {
        $this->errors[$attribute][] = $message;
    }

    public function errorMessages()
    {
        return[
            self::RULE_REQUIRED => 'The {field} field is required',
            self::RULE_EMAIL => 'The {field} field must be valid email address',
            self::RULE_MIN => 'Min length of the {field} field must be {min}',
            self::RULE_MAX => 'Max length of the {field} field must be {max}',
            self::RULE_MATCH => 'The {field} field must be the same as the {asfield} field',
            self::RULE_UNIQUE => 'With the record, this {field} already exists',
            self::RULE_EXISTS => 'With the record, this {field} already exists',
        ];
    }

    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute)
    {
        return $this->errors[$attribute][0] ?? false;
    }
}