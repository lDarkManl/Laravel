<?php
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

use src\Migrations\MigrationManager;

$migration = new MigrationManager();
$migration->run();
echo "Таблицы созданы успешно!";