<?php

namespace src\Controllers;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'config.php';

use src\Database;
use src\Forms\Fields\Input;
use src\Forms\Fields\Select;
use src\Forms\Form;
use src\Models\Model;
use src\Models\Request;
use src\Models\Status;
use src\Models\User;
use src\Request as RequestData;
use src\Json;

class ApiTableController
{
    protected RequestData $request;
    protected Database $db;
    const PAGINATION = 2;

    public function __construct()
    {
        $this->request = new RequestData();
        try {
            $this->db = Database::getInstance(DB_HOST, DB_NAME, DB_USER, DB_PASS);
        } catch (\PDOException $e) {
            $this->request->setError('Ошибка подключения к бд');
        }
    }

    public function requests(): Json
    {
        if ($this->request->isPost())
        {
            return $this->post('requests', new Request());
        }
        return $this->get('requests', new Request());

    }

    public function users(): Json
    {
        if ($this->request->isPost())
        {
            return $this->post('users', new User());
        }
        return $this->get('users', new User());
    }

    protected function get(string $table, Model $tableObj): Json
    {
        $json = new Json();
        if ($this->request->getError())
        {
            $json->setParams([
                'error' => $this->request->getError()
            ]);
            $this->request->setError('');

            return $json;
        }
        $sortField = $this->request->get('sort') ?? 'id';
        $sortOrder = $this->request->get('order') ?? 'ASC';
        $page = (int) $this->request->get('page');

        if (!$tableObj->tablesExist()) {
            $json->setParams([
                'success' => false,
                'error' => 'Таблицы ещё не созданы. Перейдите на страницу <a href="admin.php">admin</a> для их создания.'
            ]);
            return $json;
        }

        $tableDataQuery = $tableObj->query()
            ->select(["$table.*", 's.name as status_name'])
            ->leftJoin('statuses', "`$table`.`status_id`", '=', '`s`.`id`', 's')
            ->orderBy($sortField, $sortOrder);

        if ($page)
        {
            $tableDataQuery->paginate($page, self::PAGINATION);
        }

        $tableData = $tableDataQuery->get();



        $countRows = $tableObj->query()
            ->select(["count(*) as countRows"])
            ->get();

        $countPages = ceil($countRows[0]['countRows'] / static::PAGINATION);

        if (empty($tableData) && $page > 1) {
            header("Location: $table?sort=$sortField&order=$sortOrder&page=1");
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

        $json->setParams([
            'success' => true,
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
            'table' => $table,
            'tableData' => $tableData,
            'statuses' => $statuses,
            'page' => $page,
            'countPages' => $countPages,
        ]);

        return $json;
    }

    protected function post(string $table, Model $tableObj): Json
    {
        $action = $this->request->post('action');

        call_user_func_array(array($this, $action), array($tableObj));

        return $this->get($table, $tableObj);
    }

    protected function add($tableObj): void
    {
        $fields = $this->request->post();
        unset($fields['action']);
        try {
            $tableObj->create($fields);
        } catch (\PDOException $e) {
            $this->request->setError('Форма неправильно заполнена');
        }
    }

    protected function update($tableObj): void
    {
        $id = $this->request->post('row_id');
        $statusId = $this->request->post('status_id');
        $tableRow = $tableObj->find($id);
        try{
            $tableRow->update(['status_id' => $statusId]);
        }
        catch (\PDOException $e) {
            $this->request->setError('Форма неправильно заполнена');
        }

    }

    protected function delete($tableObj): void
    {
        $id = $this->request->post('delete_id');
        $tableRow = $tableObj->find($id);
        try {
            $tableRow->delete();
        } catch (\PDOException $e) {
            $this->request->setError('Ошибка удаления');
        }

    }

}