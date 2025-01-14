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
use App\Http\Controllers\Cause_Connect_CaseController;
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

Route::get('/prefectures', [PrefectureController::class, 'index']); // 都道府県情報取得
Route::post('/users', [Cause_ConnectController::class, 'store']); //　会員登録
Route::post('/login', [Cause_ConnectController::class, 'login']); // ログイン
Route::get('/user', [Cause_ConnectController::class, 'getUser'])->middleware('auth:sanctum'); //ログイン状態の保持
Route::post('/logout', [Cause_ConnectController::class, 'logout'])->middleware('auth:sanctum'); // ログアウト
Route::get('/user/me', [Cause_ConnectController::class, 'me'])->middleware('auth:sanctum'); // 会員情報取得
Route::get('/places', [PlaceController::class, 'index']); // 活動エリア取得
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/user/update', [Cause_ConnectController::class, 'update']); // 会員情報の更新
    Route::delete('/user/delete', [Cause_ConnectController::class, 'destroy']); // 会員情報削除
});
Route::post('/images', [ImageUploadController::class, 'store']);
Route::get('/images/{case_id}/{picture_type}', [ImageUploadController::class, 'show']);
Route::post('/request', [Cause_Connect_CaseController::class, 'stores']); // 依頼登録
Route::get('/activity-themes',[Activity_themeController::class,'index']); // 活動テーマ取得
Route::get('/posts',[Cause_Connect_CaseController::class,'posts']); // 登録された依頼情報の取得
Route::get('/search-posts',[Cause_Connect_CaseController::class,'index']); // ナビバーから依頼検索に対応した依頼情報の取得
Route::get('features',[FeaturesController::class,'index']); // 特徴取得
Route::get('recommended-ages',[Recommended_ageController::class,'index']); // 推奨年齢取得
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
Route::get('search-posts/{case_id}', [Cause_Connect_CaseController::class, 'show']);
