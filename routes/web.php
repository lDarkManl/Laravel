<?php

use App\Http\Controllers\TablesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotFoundController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TablesController::class, 'get'])->name('getTables');

Route::get('/profiles', [ProfileController::class, 'index'])->name('profilesGet');

Route::post('/profiles', [ProfileController::class, 'post'])->name('profilesPost');

Route::group(['prefix' => 'messages', 'as' => 'web.messages.'], function () {
    Route::get('/', [MessageController::class, 'index'])->name('list');
    Route::post('/', [MessageController::class, 'store'])->name('create');
    Route::put('/', [MessageController::class, 'update'])->name('modify');
    Route::delete('/', [MessageController::class, 'destroy'])->name('remove');
});

Route::fallback([NotFoundController::class, 'action']);
