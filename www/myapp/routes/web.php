<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GetTablesController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\NotFoundController;
use Illuminate\Support\Facades\Route;


Route::get('/', [GetTablesController::class, 'get'])->name('getTables');
Route::get('/api/v1', [GetTablesController::class, 'get'])->name('apiGetTables');

Route::get('/{table}', [TableController::class, 'get'])->name('table');
Route::get('/api/v1/{table}', [TableController::class, 'get'])->name('apiTable');

Route::get('/api/v1/{table}/{id}', [TableController::class, 'getOne'])->name('apiOne')->where('id', '[0-9]+');
Route::post('/api/v1/{table}/{id}', [TableController::class, 'deleteOne'])->name('apiDeleteOne')->where('id', '[0-9]+');

Route::post('/{table}', [TableController::class, 'post'])->name('tablePost');
Route::post('/api/v1/{table}', [TableController::class, 'post'])->name('apiTablePost');

Route::get('/admin/page', [AdminController::class, 'action'])->name('admin');
Route::get('/api/v1/admin/page', [AdminController::class, 'action'])->name('adminApi');

Route::fallback([NotFoundController::class, 'action']);
