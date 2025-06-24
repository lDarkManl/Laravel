<?php
namespace src\Controllers;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'config.php';

use src\Database;
use src\Request;
use src\Metadatas\MetadataManager;
use src\View;

class IndexController
{
    protected Request $request;
    protected MetadataManager $metadata;

    public function __construct()
    {
        $this->request = new Request();
        try {
            $db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);
            $this->metadata = new MetadataManager($db);
        } catch (\PDOException $e) {
            throw new \PDOException("Ошибка подключения к базе данных", 0, $e);
        }
    }

    public function action(): View
    {
        $view = new View();
        if (!$this->metadata->tablesExist()) {
            $view->setParams([
                'error' => 'Таблицы ещё не созданы. Перейдите на страницу <a href="admin.php">admin</a> для их создания.'
            ]);
            return $view;
        }
        $tables = $this->metadata->getTables();

        $view->setParams(['tables' => $tables]);

        return $view;
    }
}