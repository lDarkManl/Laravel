<?php
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'load-file.php';
$request = new \classes\Request('EmailForm');

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	if ($request->sendData('d_gusev_04@mail.ru', $_POST, $_SERVER)){
        header('Location: complete.php');
        exit;
    }
    else{
        echo 'Произошла ошибка. Письмо не отправлено!';
    }
	
}
$request->addField('input', 'name', 'Имя');
$request->addField('textarea', 'pass', 'Текст');
echo $request->showForm();

?>
