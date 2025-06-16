<?php
namespace classes;
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'autoload.php';


class FormField
{

    protected string $name;
    protected string $type;
    protected array $attrs;
    protected string $value;
    protected array $errors = [];
    protected string $restrictions;

    public function setName($name){
        $this->name = $name;
    }

    public function setType($type){
        $this->type = $type;
    }

    public function setAttrs($attrs){
        $this->attrs = $attrs;
    }

    public function setValue($value){
        $this->value = $value;
    }

    public function setErrors($errors){
        $this->errors = $errors;
    }

    public function setRestrictions($restrictions){
        $this->restrictions = $restrictions;
    }

    public function getName(){
        return $this->name;
    }

    public function getType(){
        return $this->type;
    }

    public function getValue(){
        return $this->value;
    }

    public function getAttrs(){
        return $this->attrs;
    }

    public function getErrors(){
        return $this->errors;
    }

    public function getRestrictions(){
        return $this->restrictions;
    }

}

