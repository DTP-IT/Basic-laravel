<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;

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
Route::get('/login', function () {
    return view('login');
});
Route::post('/sign-in', [UserController::class, 'login'])->name('user.login');
Route::prefix('item')->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('item.index');
    Route::get('/add-item', [ItemController::class, 'create'])->name('item.create');
    Route::post('/store', [ItemController::class, 'store'])->name('item.store');
    Route::get('/edit/{item}', [ItemController::class, 'edit'])->name('item.edit');
    Route::put('/update/{item}', [ItemController::class, 'update'])->name('item.update');
    Route::get('search', [ItemController::class, 'search'])->name('item.search');
    Route::get('/showSoftDelete', [ItemController::class, 'showSoftDelete'])->name('item.showSoftDelete');
});
Route::group(['middleware' => 'accessPermission'], function() {
    Route::get('user/', [UserController::class, 'index'])->name('user.index');
    Route::get('user/add-user', [UserController::class, 'create'])->name('user.create');
    Route::post('user/store', [UserController::class, 'store'])->name('user.store');
    Route::get('user/search', [UserController::class, 'search'])->name('user.search');
});
Route::prefix('category')->group(function() {
    Route::get('/', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/add-category', [CategoryController::class, 'create'])->middleware('accessPermission')->name('category.create');
    Route::post('/store', [CategoryController::class, 'store'])->middleware('accessPermission')->name('category.store');
});
Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
Route::put('/update-profile', [UserController::class, 'updateProfile'])->name('user.updateProfile');
