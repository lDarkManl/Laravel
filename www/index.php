<?php

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'autoload.php';

use src\Router;

$router = new Router();
require $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'routes.php';

$controller = $router->match();


$controllerClass = $controller['class'];
$controllerMethod = $controller['method'];

$controllerObj = new $controllerClass();

if ($router->notFound())
{
    http_response_code(404);
}

if ($router->isApi())
{
    header('Content-Type: application/json');
    $json = $controllerObj->$controllerMethod();
    echo $json->getJson();
}
else
{
    $view = $controllerObj->$controllerMethod();
    echo $view->renderTemplate();
}




