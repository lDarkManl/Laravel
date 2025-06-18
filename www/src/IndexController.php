<?php

namespace src;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'config.php';

use src\Request, src\Database, src\View, src\Status;

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
        $sortField = $this->request->get('sort') ?? 'id';
        $sortOrder = $this->request->get('order') ?? 'ASC';

        $view = new View();

        // Проверяем, существуют ли таблицы
        if (!$this->db->tablesExist()) {
            $view->setParams([
                'error' => 'Таблицы еще не созданы. Перейдите на страницу <a href="admin.php">admin</a> для их создания.'
            ]);
            return $view;
        }

        if ($this->request->isPost() && $this->request->isSetPost('name') && $this->request->isSetPost('email')) {
            $name = trim($this->request->post('name'));
            $email = $this->request->post('email');
            $this->db->add($name, $email);
            header("Location: index.php?sort=$sortField&order=$sortOrder");
            exit;

        }

        if ($this->request->isPost() && $this->request->isSetPost('request_id') && $this->request->isSetPost('status_id')) {
            $this->db->updateStatus($this->request->post('request_id'), $this->request->post('status_id'));
            header("Location: index.php?sort=$sortField&order=$sortOrder");
            exit;
        }

        if ($this->request->isPost() && $this->request->isSetPost('delete_id')) {
            $this->db->delete($this->request->post('delete_id'));
            header("Location: index.php?sort=$sortField&order=$sortOrder");
            exit;
        }

        $requests = $this->db->getAll($sortField, $sortOrder);
        $statuses = Status::getAll();

        $params = [
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
            'requests' => $requests ?: [],
            'statuses' => $statuses,
        ];

        $view->setParams($params);

        return $view;
    }
}