<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cause_ConnectController;
use App\Http\Controllers\PrefectureController;

// 新規追加: 画像アップロード用コントローラ
use App\Http\Controllers\ImageUploadController;
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
Route::post('/users', [Cause_ConnectController::class, 'store']);
Route::post('/login', [Cause_ConnectController::class, 'login']);
Route::get('/user', [Cause_ConnectController::class, 'getUser'])->middleware('auth:sanctum');
Route::post('/logout', [Cause_ConnectController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user/me', [Cause_ConnectController::class, 'me'])->middleware('auth:sanctum');
Route::get('/places', [Cause_ConnectController::class, 'index']);
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/user/update', [Cause_ConnectController::class, 'update']);
    Route::delete('/user/delete', [Cause_ConnectController::class, 'destroy']);
});

Route::post('/images', [ImageUploadController::class, 'store']);
Route::get('/images/{case_id}/{picture_type}', [ImageUploadController::class, 'show']);