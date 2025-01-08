<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cause_ConnectController;
use App\Http\Controllers\PrefectureController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\FeaturesController;
use App\Http\Controllers\Recommended_ageController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\Activity_themeController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PointController;

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
Route::get('/places', [PlaceController::class, 'index']);
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/user/update', [Cause_ConnectController::class, 'update']);
    Route::delete('/user/delete', [Cause_ConnectController::class, 'destroy']);
});
Route::post('/images', [ImageUploadController::class, 'store']);
Route::get('/images/{case_id}/{picture_type}', [ImageUploadController::class, 'show']);
Route::post('/request', [Cause_ConnectController::class, 'stores']);
Route::get('/activity-themes',[Activity_themeController::class,'index']);
Route::get('features',[FeaturesController::class,'index']);
Route::get('recommended-ages',[Recommended_ageController::class,'index']);
Route::middleware('auth:sanctum')->post('/content/upload', [ImageUploadController::class, 'upload']);

//ユーザーアイコンアップロード
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/user/icon', [UserController::class, 'uploadIcon']);
});
// 依頼画像アップロード
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/images', [ImageUploadController::class, 'store']);
    Route::get('/images/{case_id}/{picture_type}', [ImageUploadController::class, 'show']);
});
//ポイント
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/points/history', [PointController::class, 'getHistory']);
    Route::post('/points/purchase', [PointController::class, 'purchasePoints']);
});
