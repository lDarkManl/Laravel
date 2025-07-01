<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use App\Models\Status;

class AdminController extends Controller
{
    public function action(Request $request)
    {
        Artisan::call('migrate');

        $model = new Status();
        if (empty($model->query()->select()->get()))
        {
            Artisan::call('db:seed');
        }
        if ($request->is('api/v1/*'))
        {
            return response()->json(['message' => 'Таблицы созданы']);
        }
        return view('admin');
    }
}
