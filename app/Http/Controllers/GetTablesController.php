<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GetTablesController extends Controller
{
    public function get(Request $request)
    {
        $tables = [
            'requests',
            'profiles'
        ];

        foreach($tables as $table)
        {
            $modelClass = "App\\Models\\" . mb_substr(ucfirst($table), 0, -1);
            $model = new $modelClass();
            if (!$this->tableExists($model))
            {
                if ($this->isApi($request))
                {
                    return response()->json(['message' => 'Таблицы еще не созданы. Перейдите на страницу admin для их создания']);
                }
                return view('tables', ['error' => 'Таблицы еще не созданы. Перейдите на страницу admin для их создания']);
            }
        }
        if ($this->isApi($request))
        {
            return response()->json(['tables' => $tables]);
        }

        return view('tables', ['tables' => $tables]);
    }

    protected function tableExists($model): bool
    {
        return \Schema::hastable($model->getTable());
    }

    protected function isApi(Request $request): bool
    {
        return $request->is('api/v1');
    }
}
