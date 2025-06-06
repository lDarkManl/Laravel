<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin>
</head>
<body>
    <h3 class="mt-1 ms-1">Форма отправки сообщений</h3>
    <?php
    spl_autoload_register(function ($class_name) {
        require_once '/home/user/projects/dmitriy-gusev/www/' . str_replace('\\', '/', $class_name) . '.php';
    });
    $form = new \classes\Form();
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        if ($form->sendForm("d_gusev_04@mail.ru", $_POST)){
            echo "<script>alert('Ваше письмо отправлено!')</script>";
        } 
        else{
            echo "<script>alert('Произошла ошибка. Письмо не отправлено!')</script>";
        }
        
    }
    $form->addInput("name", "text", "ФИО");
    $form->addInput("password", "password", "Пароль");
    echo $form->getHtml();
    
    ?>

</body>
</html>

