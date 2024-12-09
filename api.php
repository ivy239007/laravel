<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CouseConnectController;
use App\Http\Controllers\PrefectureController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/prefectures', [PrefectureController::class, 'index']);
Route::post('/users', [CouseConnectController::class, 'store']);
Route::post('/login', [CouseConnectController::class, 'login']);
Route::get('/user', [CouseConnectController::class, 'getUser'])->middleware('auth:sanctum');
Route::post('/logout', [CouseConnectController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user/me', [CouseConnectController::class, 'me'])->middleware('auth:sanctum');
Route::get('/places', [CouseConnectController::class, 'index']);


