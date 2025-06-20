<?php

namespace src\Controllers;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'config.php';

use src\Database;
use src\Request;
use src\Status;
use src\View;
use src\Forms\Form;
use src\Forms\Fields\Input;
use src\Forms\Fields\Select;

class TableController
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
        $table = $this->request->get('table');
        $view = new View();
        
        if (!$this->db->tablesExist()) {
            $view->setParams([
                'error' => 'Таблицы ещё не созданы. Перейдите на страницу <a href="admin.php">admin</a> для их создания.'
            ]);
            return $view;
        }

        // Обработка POST-запросов
        if ($this->request->isPost()) {
            $action = $this->request->post('action');
            switch ($action) {
                case 'add':
                    $fields = $this->request->post();
                    unset($fields['action']);
                    try{
                        $this->db->add($table, $fields);
                    }
                    catch (\PDOException $e) {
                        throw new \PDOException("Ошибка добавления в базу данных", 0, $e);
                    }
                    break;
                case 'update':
                    $id = $this->request->post('row_id');
                    $statusId = $this->request->post('status_id');
                    $this->db->update($table, $id, ['status_id' => $statusId]);
                    break;
                case 'delete':
                    $id = $this->request->post('delete_id');
                    $this->db->delete($table, $id);
                    break;
            }
            header("Location: table.php?table=$table&sort=$sortField&order=$sortOrder");
            exit;
        }

        $tableData = $this->db->getAll($table, $sortField, $sortOrder);
        $statuses = Status::getAll();
        $columns = $this->db->getTableColumns($table);

        // Создаём форму
        $form = new Form();

        foreach ($columns as $column) {
            if ($column === 'status_id' or $column === 'id') continue;

            $label = ucfirst($column);
            $form->addField(new Input($column, $label));
        }

        if (in_array('status_id', $columns)) {
            $statusOptions = Status::getAll();
            $form->addField(new Select('status_id', 'Статус', $statusOptions));
        }

        $actionField = new Input("action", "");
        $actionField->setValue("add");
        $actionField->setType("hidden");
        $form->addField($actionField);

        $view->setParams([
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
            'table' => $table,
            'tableData' => $tableData ?: [],
            'statuses' => $statuses,
            'columns' => $columns,
            'form' => $form,
        ]);

        return $view;
    }
}