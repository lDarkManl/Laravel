<?php
namespace classes;
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'autoload.php';
       
class Response{

    protected $form = null;
    protected $message = '';

    public function getForm(){
        return $this->form;
    }

    public function getMessage(){
        return $this->message;
    }

    public function setMessage($message){
        $this->message = $message;
    }

    public function setForm($form){
        $this->form = $form;
    }
}



