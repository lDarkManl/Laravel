<?php

namespace src\Controllers;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'config.php';

use src\Database;
use src\Json;
use src\Metadatas\MetadataManager;
use src\Request;

class ApiGetTablesController
{
    protected Request $request;
    protected MetadataManager $metadata;

    public function __construct()
    {
        $this->request = new Request();
        try {
            $db = Database::getInstance(DB_HOST, DB_NAME, DB_USER, DB_PASS);
            $this->metadata = new MetadataManager($db);
        } catch (\PDOException $e) {
            $this->request->setError('Ошибка подключения к бд');
        }
    }
    public function get(): Json
    {
        $json = new Json();

        if ($this->request->getError())
        {
            $json->setParams([
                'success' => false,
                'error' => $this->request->getError()
            ]);
            $this->request->setError('');

            return $json;
        }

        if (!$this->metadata->tablesExist()) {
            $json->setParams([
                'success' => false,
                'error' => 'Таблицы ещё не созданы. Откройте admin.php для их создания'
            ]);
            return $json;
        }
        $tables = $this->metadata->getTables();

        $json->setParams([
            'success' => true,
            'tables' => $tables
        ]);

        return $json;
    }

}