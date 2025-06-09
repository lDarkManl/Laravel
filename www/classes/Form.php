<?php
namespace classes;
//Класс для работы с формой (создание и отправка)
abstract class Form
{
    protected $formData = [];

    public function addInput($name, $placeholder = '', $required = false)
    {
        $requiredAttr = $required ? 'required' : '';
        $inputData = [
            'field' => 'input',
            'name' => $name,
            'placeholder' => $placeholder,
            'requiredAttr' => $requiredAttr,
        ];
        $this->formData[] = $inputData;
    }

    public function addTextarea($name, $placeholder = '', $required = false){
        $requiredAttr = $required ? 'required' : '';
        $inputData = [
            'field' => 'textarea',
            'name' => $name,
            'placeholder' => $placeholder,
            'requiredAttr' => $requiredAttr,
        ];
        $this->formData[] = $inputData;
    }

    public function render($tmp) {
        if(file_exists('templates/'.$tmp.'.tpl.php')) {
            ob_start();
            require_once 'templates/'.$tmp.'.tpl.php';
            return ob_get_clean();
        }
    }

    public abstract function sendForm($to, $data);
}
?>
