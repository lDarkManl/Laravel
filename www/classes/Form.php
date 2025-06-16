<?php
namespace classes;
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'autoload.php';

use \classes\interfaces\FormInterface, \classes\FormField, \classes\FieldValidator;

abstract class Form implements \classes\interfaces\FormInterface{

	protected array $fields = [];

	public function addField(\classes\FormField $field){
		$this->fields[] = $field;
	}

	public function getFields(){
		return $this->fields;
	}

    public function populate(array $data): void{
    	foreach($this->fields as $field){
    		$field->setValue($data[$field->getName()]);
    	}
    }

    public function isValid(): bool
	{
		$error = false;
	    foreach ($this->fields as $field) {
	        $validator = new FieldValidator($field);

	        $errors = $validator->validate();
	        if (!empty($errors)) {
	        	$error = true;
	            $field->setErrors($errors);
	            
	        }
	    }
	    return !$error;
	}

    public abstract function submit($address): bool;
}