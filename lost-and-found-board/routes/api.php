<?php

use App\Http\Controllers\Api\ItemController;
use Illuminate\Support\Facades\Route;

Route::get('/items', [ItemController::class, 'index']);
Route::post('/items', [ItemController::class, 'store']);
Route::get('/items/{item}', [ItemController::class, 'show']);
Route::patch('/items/{item}/claim', [ItemController::class, 'markClaimed']);
