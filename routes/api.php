<?php

use App\Http\Controllers\coinController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', UserController::class . '@register');
Route::get('/users', UserController::class . '@getListUser');
Route::post('/login',  UserController::class . '@login');
Route::post('/logout',  UserController::class . '@logout');
Route::post('/users/change-role', UserController::class . '@changeRole');
Route::get('/getCoinIntoDatabase', coinController::class . '@getCoinIntoDatabase');
Route::get('/updateDatabeCoin', coinController::class . '@updateDatabeCoin');
Route::get('/updateChartCoinIntoDatabase', coinController::class . '@updateChartCoinIntoDatabase');
Route::get('/callvalue/forHourto' , coinController::class .'@forHourto');
