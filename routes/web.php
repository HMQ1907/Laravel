<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', [AuthController::class, 'index']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/', [AuthController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::get('/{id}', [UserController::class, 'getUserById'])->name('user.detail');
        Route::post('/create', [UserController::class, 'create'])->name('user.create');
        Route::put('/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/{id}', [UserController::class, 'delete'])->name('user.delete');
        Route::patch('/{id}', [UserController::class, 'block'])->name('user.block');
    });

    Route::group(['prefix' => 'customer'], function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customer.index');
        Route::post('/create', [CustomerController::class, 'create'])->name('customer.create');
        Route::put('/{id}', [CustomerController::class, 'update'])->name('customer.update');
        Route::delete('/{id}', [CustomerController::class, 'delete'])->name('customer.delete');
        Route::get('/export-customers', [CustomerController::class, 'export'])->name('customer.export');
        Route::post('/import-customers', [CustomerController::class, 'import'])->name('customer.import');
    });

    Route::group(['prefix'=>'product'],function () {
        Route::get('/',[ProductController::class,'index'])->name('product.index');
        Route::get('add',[ProductController::class,'add'])->name('product.add');
        Route::post('create',[ProductController::class,'create'])->name('product.create');
        Route::get('/edit/{id}',[ProductController::class,'getProduct'])->name('product.edit');
        Route::post('update/{id}',[ProductController::class,'update'])->name('product.update');
        Route::delete('/{id}',[ProductController::class,'delete'])->name('product.delete');
    });
});
