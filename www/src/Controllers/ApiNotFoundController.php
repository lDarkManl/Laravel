<?php
namespace src\Controllers;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'config.php';

use src\Json;

class ApiNotFoundController
{
    public function get()
    {
        $json = new Json();
        $json->setParams([
            'success' => false,
            'error' => 'Ошибка 404'
        ]);
        return $json;
    }
}