<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers as Api;

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

Route::post('/user/login', [Api\UserController::class, 'login']);
Route::put('/user/{id}/auth', [Api\UserController::class, 'updateAuth']);

Route::resource('/surat/pesanan', Api\PesananSuratController::class);
Route::resource('/surat', Api\SuratController::class);
Route::resource('/user', Api\UserController::class);