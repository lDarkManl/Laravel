<?php

namespace App\Http\Controllers;

use App\Services\TableService;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Profile;

class TablesController extends Controller
{
    public function get(Request $request)
    {
        $models = [
            Message::class,
//            Profile::class
        ];

        $tables = [];

        foreach($models as $modelClass)
        {
            $model = new $modelClass();
            $tables[] = $model->getTable();
            if (!$model->tableExists())
            {
                session(['error' => 'Таблицы еще не созданы.']);
            }
        }

        return view('tables', ['tables' => $tables]);
    }
}
