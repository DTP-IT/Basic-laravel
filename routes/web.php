<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/dashboard', [UserController::class, 'login']);

Route::prefix('item')->group(function () {
    Route::get('/', [ItemController::class, 'index']);
    Route::get('/add-item', [ItemController::class, 'create']);
    Route::post('/store', [ItemController::class, 'store']);
    Route::get('/edit/{item}', [ItemController::class, 'edit']);
    Route::put('/update/{item}', [ItemController::class, 'update']);
    Route::get('search', [ItemController::class, 'search']);
    Route::get('/showSoftDelete', [ItemController::class, 'showSoftDelete']);
});
Route::prefix('user')->group(function() {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/add-user', [UserController::class, 'create']);
    Route::post('/store', [UserController::class, 'store']);
    Route::get('search', [UserController::class, 'search']);
});

Route::prefix('category')->group(function() {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/add-category', [CategoryController::class, 'create']);
    Route::post('/store', [CategoryController::class, 'store']);
});
Route::get('/profile', [UserController::class, 'profile']);
Route::put('/update-profile', [UserController::class, 'updateProfile']);
