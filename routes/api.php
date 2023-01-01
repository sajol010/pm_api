<?php

use App\Http\Controllers\{AuthController, ProductController, CategoryController};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('register', [AuthController::class, 'register'])->name('user.register');
Route::post('login', [AuthController::class, 'authenticate'])->name('user.login');

Route::group(['middleware'=>'auth:sanctum'], function (){
    Route::apiResource('products', ProductController::class);
    Route::apiResource('categories', CategoryController::class);
});

