<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = 'd_gusev_04@mail.ru';
    $name = $_POST['name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];
    $text = $_POST['text'];
    mail($email, $last_name . ' ' . $name, $text . '\n' . $phone);
    header("Location: index.php");
    exit();
}
?>