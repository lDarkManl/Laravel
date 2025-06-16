<?php
namespace classes;
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'autoload.php';

use \classes\Request, \classes\Response;

class FormController{

    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    public function get(){
        $form = $this->buildForm('\classes\EmailForm');
        $response = new Response();
        $response->setForm($form);
        return $response;
    }

    public function post(){
        //Отправка формы
        $form = $this->buildForm('\classes\EmailForm');
        $response = new Response();
        $response->setForm($form);
        if ($form->isValid()){

            if ($form->submit('sdfjsl@mail.ru')){
                $response->setMessage('Ваше сообщение передано!');
                return $response;
            }

            else{
                $response->setMessage('Передача не удалась!');
                return $response;
            }
        }
        else{
            $response->setMessage('Форма заполнена неправильно!');
            return $response;
        }
    }

    protected function buildForm($formClass){
        //Создание и наполнение формы
        $form = new $formClass();

        $inputField = new \classes\FormField();
        $inputField->setName('name');
        $inputField->setType('input');
        $inputField->setAttrs([
            'required' => '',
            'placeholder' => 'Имя'
        ]);
        $inputField->setRestrictions('required|max:100');

        $textAreaField = new \classes\FormField();
        $textAreaField->setName('text');
        $textAreaField->setType('textarea');
        $textAreaField->setAttrs([
            'placeholder' => 'Текст'
        ]);
        $textAreaField->setRestrictions('max:100');

        $form->addField($inputField);
        $form->addField($textAreaField);

        $inputField->setValue($this->request->post($inputField->getName()));
        $textAreaField->setValue($this->request->post($textAreaField->getName()));

        return $form;
    }
}

