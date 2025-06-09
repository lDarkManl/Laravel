<?php
namespace classes;
require_once $_SERVER['DOCUMENT_ROOT'] . '/load-file.php';

class EmailForm extends \classes\Form 
{
    public function sendForm($to, $data)
    {
        $message = '';
        foreach ($data as $key => $value) {
            $message .= "$key: $value\n";
        }

        $headers = "From: no-reply@example.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        return mail($email, "Данные формы", $message, $headers);

    }
}
?>