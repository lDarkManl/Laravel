<?php
namespace src\Controllers;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'config.php';

use src\Database;
use src\Request;
use src\Models\Status;
use src\Models\User;
use src\Models\Request as RequestModel;
use src\View;
use src\Forms\Form;
use src\Forms\Fields\Input;
use src\Forms\Fields\Select;

class TableController
{
    protected Request $request;
    protected Database $db;
    const PAGINATION = 2;

    public function __construct()
    {
        $this->request = new Request();
        $this->db = Database::getInstance(DB_HOST, DB_NAME, DB_USER, DB_PASS);
    }

    public function action(): View
    {
        $sortField = $this->request->get('sort') ?? 'id';
        $sortOrder = $this->request->get('order') ?? 'ASC';
        $table = $this->request->get('table');
        $page = (int) ($this->request->get('page') ?? 1);
        $view = new View();

        $modelClass = $table === 'users' ? User::class : RequestModel::class;
        $tableObj = new $modelClass();

        if (!$tableObj->tablesExist()) {
            $view->setParams([
                'error' => 'Таблицы ещё не созданы. Перейдите на страницу <a href="admin.php">admin</a> для их создания.'
            ]);
            return $view;
        }

        if ($this->request->isPost()) {
            $action = $this->request->post('action');
            switch ($action) {
                case 'add':
                    $fields = $this->request->post();
                    unset($fields['action']);
                    try {
                        $tableObj->create($fields);
                    } catch (\PDOException $e) {
                        $view->setParams([
                            'error' => 'Форма неправильно заполнена'
                        ]);
                        return $view;
                    }

                    break;
                case 'update':
                    $id = $this->request->post('row_id');
                    $statusId = $this->request->post('status_id');
                    $tableRow = $tableObj->find($id);
                    $tableRow->update(['status_id' => $statusId]);
                    break;
                case 'delete':
                    $id = $this->request->post('delete_id');
                    $tableRow = $tableObj->find($id);
                    $tableRow->delete();
                    break;
            }
            header("Location: table.php?table=$table&sort=$sortField&order=$sortOrder&page=$page");
            exit;
        }

        $tableData = $tableObj->query()
            ->select(["$table.*", 's.name as status_name'])
            ->leftJoin('statuses', "`$table`.`status_id`", '=', '`s`.`id`', 's')
            ->orderBy($sortField, $sortOrder)
            ->limit(self::PAGINATION)
            ->offset(($page - 1) * self::PAGINATION)
            ->get();

        if (empty($tableData) && $page > 1) {
            header("Location: table.php?table=$table&sort=$sortField&order=$sortOrder&page=1");
            exit;
        }

        $statuses = (new Status())->query()->get();
        $columns = $tableObj->getTableColumns();

        $form = new Form();
        foreach ($columns as $column) {
            if ($column === 'status_id' || $column === 'id') continue;
            $label = ucfirst($column);
            $form->addField(new Input($column, $label));
        }

        if (in_array('status_id', $columns)) {
            $statusOptions = array_column($statuses, 'name', 'id');
            $form->addField(new Select('status_id', 'Статус', $statusOptions));
        }

        $actionField = new Input('action', '');
        $actionField->setValue('add');
        $actionField->setType('hidden');
        $form->addField($actionField);

        $view->setParams([
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
            'table' => $table,
            'tableData' => $tableData,
            'statuses' => $statuses,
            'columns' => $columns,
            'form' => $form,
            'page' => $page,
        ]);

        return $view;
    }
}