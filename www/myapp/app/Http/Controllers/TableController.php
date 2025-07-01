<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Status;
use Illuminate\Support\Facades\View;

class TableController extends Controller
{
    protected $pagination = 1;

    public function get(Request $request, string $table)
    {
        if ($this->isApi($request))
        {
            return $this->getJson($request, $table);
        }

        return $this->getHtml($request, $table);

    }

    protected function getJson(Request $request, string $table)
    {
        $modelClass = "App\\Models\\" . mb_substr(ucfirst($table), 0, -1);

        if (!class_exists($modelClass)) {
            return response()->json(['message' => 'Страница не найдена'], 404);
        }

        $model = new $modelClass();

        if ($request->session()->has('error')) {
            return response($request)->json(['message' => $request->session()->get('error')]);
        }

        $sortField = $request->input('sort', 'id');
        $sortOrder = $request->input('order', 'asc');
        $page = $request->input('page', 0);

        if (!$this->tableExists($model)) {
            response()->json(['message' => 'Таблицы еще не созданы. Перейдите на страницу admin для их создания']);
        }

        $query = $model->leftJoin('statuses as s', $model->getTable() . '.status_id', '=', 's.id');

        $columns = array_map(function ($column) use ($model) {
            return $model->getTable() . '.' . $column;
        }, $model->getTableColumns());

        $selectColumns = array_merge($columns, ['s.name as status_name']);

        $paginator = $query->select($selectColumns)
            ->orderBy($sortField, $sortOrder)
            ->paginate($this->pagination, ['*'], 'page', $page);

        if ($page !== 0)
        {
            $tableData = $paginator->items();
        }
        else
        {
            $tableData = $query->select($selectColumns)->orderBy($sortField, $sortOrder)->get();
        }

        if (empty($tableData) and $page > 1)
        {
            return redirect()->to(route("apiTable", [
                'table' => $table,
                'sort' => $sortField,
                'order' => $sortOrder,
                'page' => $paginator->lastPage()
            ]));
        }

        return response()->json([
            'tableData' => $tableData,
            'page' => $page,
            'countPages' => $paginator->lastPage(),
        ]);
    }

    protected function getHtml(Request $request, string $table)
    {
        $modelClass = "App\\Models\\" . mb_substr(ucfirst($table), 0, -1);

        if (!class_exists($modelClass)) {
            abort(404, "Модель не найдена");
        }

        $model = new $modelClass();

        if ($request->session()->has('error')) {
            return view('table');
        }

        $sortField = $request->input('sort', 'id');
        $sortOrder = $request->input('order', 'asc');
        $page = $request->input('page', 1);

        if (!$this->tableExists($model)) {
            session(['error' => 'Таблицы еще не созданы. Перейдите на страницу admin для их создания']);
        }

        $query = $model->leftJoin('statuses as s', $model->getTable() . '.status_id', '=', 's.id');

        $columns = array_map(function ($column) use ($model) {
            return $model->getTable() . '.' . $column;
        }, $model->getTableColumns());

        $selectColumns = array_merge($columns, ['s.name as status_name']);

        $paginator = $query->select($selectColumns)
            ->orderBy($sortField, $sortOrder)
            ->paginate($this->pagination, ['*'], 'page', $page);

        $tableData = $paginator->items();

        if (empty($tableData) and $page > 1)
        {
            return redirect()->to(route("table", [
                'table' => $table,
                'sort' => $sortField,
                'order' => $sortOrder,
                'page' => $paginator->lastPage()
            ]));
        }

        $statuses = Status::all();

        $formFields = [];

        foreach ($model->getTableColumns() as $column) {
            if ($column === 'id' || $column === 'status_id') continue;
            $formFields[] = [
                'name' => $column,
                'label' => ucfirst($column),
                'type' => 'text'
            ];
        }

        if (in_array('status_id', $model->getTableColumns())) {
            $formFields[] = [
                'name' => 'status_id',
                'type' => 'select',
                'label' => 'Статус',
                'options' => $statuses->pluck('name', 'id')->toArray()
            ];
        }

        $formFields[] = [
            'name' => 'action',
            'type' => 'hidden',
            'value' => 'add'
        ];

        return view('table', [
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
            'table' => $table,
            'tableData' => $tableData,
            'statuses' => $statuses,
            'columns' => $model->getTableColumns(),
            'formFields' => $formFields,
            'page' => $page,
            'countPages' => $paginator->lastPage(),
            'paginator' => $paginator,
        ]);

    }

    protected function isApi(Request $request): bool
    {
        return $request->is('api/v1/*');
    }

    public function getOne(Request $request, string $table, int $id)
    {
        $modelClass = "App\\Models\\" . mb_substr(ucfirst($table), 0, -1);

        if (!class_exists($modelClass)) {
            return response()->json(['message' => 'Страница не найдена'], 404);
        }

        $model = new $modelClass();

        if (!$this->tableExists($model)) {
            response()->json(['message' => 'Таблицы еще не созданы. Перейдите на страницу admin для их создания']);
        }

        $query = $model->leftJoin('statuses as s', $model->getTable() . '.status_id', '=', 's.id');

        $columns = array_map(function ($column) use ($model) {
            return $model->getTable() . '.' . $column;
        }, $model->getTableColumns());

        $selectColumns = array_merge($columns, ['s.name as status_name']);

        $row = $query->select($selectColumns)->where($model->getTable() . ".id", $id)->first();

        if (empty($row))
        {
            return response()->json([
                'message' => 'Записи с таким id нет в таблице'
            ]);
        }

        return response()->json([
            'row' => $row
        ]);

    }

    public function deleteOne(Request $request, string $table, int $id)
    {
        $modelClass = "App\\Models\\" . mb_substr(ucfirst($table), 0, -1);
        if (!class_exists($modelClass))
        {
            return response()->json(['message' => 'Модель не найдена']);
        }

        $model = $modelClass::find($id);

        if (!$model)
        {
            return response()->json(['message' => 'Запись не найдена']);
        }

        try{
            $model->delete();
        } catch (\PDOException $exception) {
            return response()->json(['message' => 'Ошибка удаления']);
        }

        return response()->json(['message' => 'Запись удалена']);
    }

    public function post(Request $request, string $table)
    {
        if ($this->isApi($request))
        {
            return $this->postJson($request, $table);
        }

        return $this->postHtml($request, $table);
    }


    protected function postHtml(Request $request, string $table)
    {
        $action = $request->input('action');

        if (!method_exists($this, $action)) {
            return redirect()->route('table', ['table' => $table])->withErrors(['error' => 'Действие не поддерживается']);

        }

        try {
            call_user_func_array(array($this, $action), array($request, $table));
        } catch (\Exception $exception) {
            return redirect()->route('table', ['table' => $table])->withErrors(['error' => $exception->getMessage()]);
        }

        return redirect()->route("table", ['table' => $table]);

    }

    protected function postJson(Request $request, string $table)
    {
        $action = $request->input('action');

        if (!method_exists($this, $action)) {
            return response()->json(['message' => 'Действие не поддерживается']);
        }

        try {
            call_user_func_array(array($this, $action), array($request, $table));
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }

        return response()->json(['message' => 'Действие выполнено успешно!']);

    }

    protected function add(Request $request, string $table)
    {
        $modelClass = "App\\Models\\" . mb_substr(ucfirst($table), 0, -1);
        if (!class_exists($modelClass)) {
            throw new \Exception("Модель не найдена");
        }

        $model = new $modelClass();

        $fields = $request->except(['action']);

        try{
            $model::create($fields);
        } catch (\PDOException $exception) {
            throw new \Exception('Форма неправильно заполнена');
        }
    }

    protected function update(Request $request, string $table)
    {
        $modelClass = "App\\Models\\" . mb_substr(ucfirst($table), 0, -1);

        if (!class_exists($modelClass))
        {
            throw new \Exception('Модель не найдена');
        }

        $id = $request->input('row_id');
        $statusId = $request->input('status_id');

        $model = $modelClass::find($id);

        if (!$model)
        {
            throw new ModelNotFoundException('Запись не найдена');
        }

        try{
            $model->update(['status_id' => $statusId]);
        } catch (\PDOException $exception) {
            throw new \Exception('Ошибка обновления');
        }
    }

    protected function delete(Request $request, string $table)
    {
        $modelClass = "App\\Models\\" . mb_substr(ucfirst($table), 0, -1);
        if (!class_exists($modelClass))
        {
            throw new \Exception('Модель не найдена');
        }

        $id = $request->input('delete_id');

        $model = $modelClass::find($id);

        if (!$model)
        {
            throw new ModelNotFoundException('Запись не найдена');
        }

        try{
            $model->delete();
        } catch (\PDOException $exception) {
            throw new \Exception('Ошибка удаления');
        }
    }

    protected function tableExists($model): bool
    {
        return \Schema::hastable($model->getTable());
    }
}
