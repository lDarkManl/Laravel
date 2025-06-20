<?php

namespace src\Controllers;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'config.php';

use src\Database;
use src\Request;
use src\View;

class IndexController
{
    protected Request $request;
    protected Database $db;

    public function __construct()
    {
        $this->request = new Request();
        try
        {
            $this->db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);
        } catch (\PDOException $e) {
            throw \PDOException($e->getMessage(), 0, $e);
        }
    }

    public function action(): View
    {
        $view = new View();
        if (!$this->db->tablesExist()) {
            $view->setParams([
                'error' => 'Таблицы ещё не созданы. Перейдите на страницу <a href="admin.php">admin</a> для их создания.'
            ]);
            return $view;
        }
        $tables = $this->db->getTables();

        $view->setParams(['tables' => $tables]);

        return $view;
    }
}