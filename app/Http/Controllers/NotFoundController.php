<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotFoundController extends Controller
{
    public function action(Request $request)
    {
        if ($request->is('api/v1/*'))
        {
            return response()->json(['message' => 'Страница не найдена'], 404);
        }

        abort(404, "Страница не найдена");
    }
}
