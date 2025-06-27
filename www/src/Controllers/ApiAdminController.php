<?php

namespace src\Controllers;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'config.php';

use src\Migrations\MigrationManager;
use src\Json;

class ApiAdminController
{
    public function get(): Json
    {
        $migration = new MigrationManager();
        $migration->run();

        $json = new Json();
        $json->setParams([
            'success' => true,
            'message' => 'База данных успешно наполнена!',
        ]);
        return $json;
    }
}