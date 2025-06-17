<?php

namespace src\Forms;

use src\Forms\Fields\Input;

class EmailForm extends BaseForm
{
    public function __construct()
    {
        $this->fields[] = new Input('email', 'Email');
        $this->fields[] = new Input('message', 'Сообщение');
    }

    public function send(string $address): bool
    {
        $message = '';
        foreach ($this->fields as $field) {
            $message .= $field->getName() . ': ' . $field->getValue() . "\n";
        }

        $headers = "From: no-reply@example.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        return mail($address, "Данные формы", $message, $headers);
    }
}