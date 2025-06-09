<?php
namespace classes;
require_once $_SERVER['DOCUMENT_ROOT'] . '/load-file.php';

class JsonForm extends \classes\Form 
{
    public function sendForm($to, $data)
    {
        return file_put_contents($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $to . '.json', json_encode($data));

    }
}
?>