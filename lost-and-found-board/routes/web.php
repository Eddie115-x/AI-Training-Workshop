<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/items');

Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
Route::post('/items', [ItemController::class, 'store'])->name('items.store');
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
Route::patch('/items/{item}/claim', [ItemController::class, 'markClaimed'])->name('items.markClaimed');
