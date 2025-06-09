<?php
spl_autoload_register(function ($class_name) {
    $path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';
    if (file_exists($path)){
        require_once $path;
    }
});
?>