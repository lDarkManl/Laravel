<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'autoload.php';

$request = new \classes\Request();
$controller = new \classes\FormController($request);

$response = $controller->get();

if ($request->isPost()){
    $response = $controller->post();
    echo $response->getMessage();
}


?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin>
</head>
<body>
    <h3 class="mt-1 ms-1">Форма отправки сообщений</h3>
    <form method="POST" class="ms-1">
        <? foreach ($response->getForm()->getFields() as $field): ?>
            <? if (!empty($field->getErrors())): ?>
                <? foreach ($field->getErrors() as $error): ?>
                    <div><?=$error ?></div>
                <? endforeach; ?>
            <? endif; ?>
        <div class="input-group mb-3 mt-3 flex-column">
            <label for="<?=$field->getName();?>"><?=$field->getAttrs()['placeholder']?></label>
            <<?=$field->getType();?> type="text" name="<?=$field->getName();?>"
                <? foreach ($field->getAttrs() as $attr => $value): ?> 
                    <?= ($attr === 'required') ? $attr : $attr . '="' . $value . '"';?>
                <? endforeach;?> class="form-control w-25"></<?=$field->getType();?>>
        </div>
        <? endforeach; ?>
        
        <button type="submit" class="btn btn-primary">Отправить</button>
    </form>
</body>
</html>



