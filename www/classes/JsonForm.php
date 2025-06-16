<?php
namespace classes;
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'autoload.php';

use \classes\Form;

class JsonForm extends Form{

    public function submit($address): bool{
        $data = [];
        foreach ($this->fields as $field) {
            $data[$field->getName()] = $field->getValue();
        }
        return file_put_contents($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $address . '.json', json_encode($data));

    }
}