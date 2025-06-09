<?php
namespace classes;
require_once $_SERVER['DOCUMENT_ROOT'] . '/load-file.php';
//Класс для работы с запросом от пользователя
class Request
{
    protected $form;

    public function __construct($formType){
        $className = '\classes\\' . $formType;
        $this->form = new $className();

    }

    public function addField($field, $name, $placeholder = '', $required = false){
        call_user_func_array(array($this->form, 'add' . $field), array($name, $placeholder, $required));
    }

    public function showForm(){
        return $this->form->render('index');
    }

    //Валидирует данные и отдает форме на отправку
    public function sendData($to, $data, $server){
        if (strpos($server['HTTP_USER_AGENT'], 'Postman') !== false)
            return false;
        foreach ($data as $field => $fieldData){
            $data[$field] = strip_tags($fieldData);
        }

        return $this->form->sendForm($to, $data);
         
        
    }
}
?>