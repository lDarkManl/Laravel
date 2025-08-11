<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Profile;
use Illuminate\Http\Request;

class ApiTablesController extends Controller
{
    public function action(Request $request)
    {
        $models = [
            Message::class,
            Profile::class
        ];

        $tables = [];

        foreach($models as $modelClass)
        {
            $model = new $modelClass();
            $tables[] = $model->getTable();
            if (!$model->tableExists())
            {
                return response()->json(['message' => 'Таблица не найдена']);
            }
        }

        return response()->json(['tables' => $tables]);
    }
}
