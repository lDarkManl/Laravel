<?php
namespace src\Controllers;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'config.php';

use src\Database;
use src\Request;
use src\Metadatas\MetadataManager;
use src\View;

class GetTablesController
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

    public function get(): View
    {
        $view = new View();

        if ($this->request->getError())
        {
            $view->setParams([
                'error' => $this->request->getError()
            ]);
            $this->request->setError('');

            return $view;
        }

        if (!$this->metadata->tablesExist()) {
            $view->setParams([
                'error' => 'Таблицы ещё не созданы. Перейдите на страницу <a href="admin.php">admin</a> для их создания.'
            ]);
            return $view;
        }
        $tables = $this->metadata->getTables();

        $view->setTplName('tables');

        $view->setParams(['tables' => $tables]);

        return $view;
    }
}