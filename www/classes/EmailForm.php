<?php
namespace classes;
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'autoload.php';

use \classes\Form;

class EmailForm extends Form{

    public function submit($address): bool{
    	$message = '';
        foreach ($this->fields as $field) {
            $message .= $field->getName() . ':' . $field->getValue() . "\n";
        }

        $headers = "From: no-reply@example.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        return mail($address, "Данные формы", $message, $headers);

    }
}