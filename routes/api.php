<?php

use App\Http\Controllers\api\ApiProfileController;
use App\Http\Controllers\api\ApiMessageController;
use App\Http\Controllers\api\ApiTablesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [ApiTablesController::class, 'action']);
Route::resource('/profiles', ApiProfileController::class);
Route::resource('/messages', ApiMessageController::class);
