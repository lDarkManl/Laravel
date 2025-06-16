<?php
namespace classes;
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'autoload.php';

use \classes\ValidateLength, \classes\ValidateRequired;

class FieldValidator
{
    protected FormField $field;

    public function __construct($field){
        $this->field = $field;
    }

    public function validate(): array
    {
        $restrictions = $this->field->getRestrictions();
        $restrictionsArray = [];

        foreach (explode("|", $restrictions) as $condition){
            $conditionSplit = explode(":", $condition);
            $restrictionsArray[$conditionSplit[0]] = array_key_exists(1, $conditionSplit) ? $conditionSplit[1] : '';
        }

        $mapValidators = [
            'max' => '\classes\ValidateLength',
            'required'=> '\classes\ValidateRequired'
        ];

        $validators = [];

        foreach ($restrictionsArray as $key => $value){
            if ($value !== '')
                $validators[] = new $mapValidators[$key]((int)$value);
            else
                $validators[] = new $mapValidators[$key]();
        }

        $errors = [];

        foreach ($validators as $validator) {
            if (!$validator->validate($this->field->getValue())) {
                $errors[] = $validator->showError();
            }
        }

        return $errors;
    }
}

