<?php

/** @var TYPE_NAME $router */

use src\Controllers\ApiGetTablesController;
use src\Controllers\ApiTableController;
use src\Controllers\ApiAdminController;
use src\Controllers\TableController;
use src\Controllers\GetTablesController;


$router->get('^/$', ['class' => GetTablesController::class, 'method' => 'get']);
$router->get('^/index\.php$', ['class' => GetTablesController::class, 'method' => 'get']);

$router->get('^/users$', ['class' => TableController::class, 'method' => 'users']);
$router->get('^/users\?sort=[^&]+&order=(?i)(asc|desc)&page=\d+$', ['class' => TableController::class, 'method' => 'users']);

$router->get('^/requests$', ['class' => TableController::class, 'method' => 'requests']);
$router->get('^/requests\?sort=[^&]+&order=(?i)(asc|desc)&page=\d+$', ['class' => TableController::class, 'method' => 'requests']);

$router->post('^/users$', ['class' => TableController::class, 'method' => 'users']);
$router->post('^/users\?sort=[^&]+&order=(?i)(asc|desc)&page=\d+$', ['class' => TableController::class, 'method' => 'users']);

$router->post('^/requests$', ['class' => TableController::class, 'method' => 'requests']);
$router->post('^/requests\?sort=[^&]+&order=(?i)(asc|desc)&page=\d+$', ['class' => TableController::class, 'method' => 'requests']);




$router->get('^/api/v1$', ['class' => ApiGetTablesController::class, 'method' => 'get']);
$router->get('^/api/v1$', ['class' => ApiGetTablesController::class, 'method' => 'get']);

$router->get('^/api/v1/users$', ['class' => ApiTableController::class, 'method' => 'users']);
$router->get('^/api/v1/users\?sort=[^&]+&order=(?i)(asc|desc)&page=\d+$', ['class' => ApiTableController::class, 'method' => 'users']);

$router->get('^/api/v1/requests$', ['class' => ApiTableController::class, 'method' => 'requests']);
$router->get('^/api/v1/requests\?sort=[^&]+&order=(?i)(asc|desc)&page=\d+$', ['class' => ApiTableController::class, 'method' => 'requests']);

$router->post('^/api/v1/users$', ['class' => ApiTableController::class, 'method' => 'users']);
$router->post('^/api/v1/users\?sort=[^&]+&order=(?i)(asc|desc)&page=\d+$', ['class' => ApiTableController::class, 'method' => 'users']);

$router->post('^/api/v1/requests$', ['class' => ApiTableController::class, 'method' => 'requests']);
$router->post('^/api/v1/requests\?sort=[^&]+&order=(?i)(asc|desc)&page=\d+$', ['class' => ApiTableController::class, 'method' => 'requests']);

$router->get('^/api/v1/admin$', ['class' => ApiAdminController::class, 'method' => 'get']);








