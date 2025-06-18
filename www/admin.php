<?php
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'config.php';

use src\Database;

try {
    $db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);
    $db->createTable();
    echo 'Таблицы успешно созданы или уже существуют. <a href="index.php">index</a>';
} catch (Exception $e) {
    echo "Ошибка при создании таблиц: " . $e->getMessage();
}