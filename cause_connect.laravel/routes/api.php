<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Cause_ConnectController,
    Cause_Connect_CaseController,
    PrefectureController,
    PlaceController,
    FeaturesController,
    Recommended_ageController,
    StateController,
    Activity_themeController,
    ImageUploadController,
    UserController,
    PointController,
    RequestReportController,
    ActController,
    SupController,
    ParticipantsController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ✅ 認証関連
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());
    Route::put('/user/update', [Cause_ConnectController::class, 'update']);
    Route::delete('/user/delete', [Cause_ConnectController::class, 'destroy']);
    Route::post('/logout', [Cause_ConnectController::class, 'logout']);
    Route::get('/user/me', [Cause_ConnectController::class, 'me']);
    Route::post('/user/icon', [UserController::class, 'uploadIcon']);
    Route::get('/points/history', [PointController::class, 'getHistory']);
    Route::post('/points/purchase', [PointController::class, 'purchasePoints']);
});

Route::post('/users', [Cause_ConnectController::class, 'store']); // 会員登録
Route::post('/login', [Cause_ConnectController::class, 'login']); // ログイン

// ✅ 各種情報取得
Route::get('/prefectures', [PrefectureController::class, 'index']);
Route::get('/places', [PlaceController::class, 'index']);
Route::get('/activity-themes', [Activity_themeController::class, 'index']);
Route::get('/features', [FeaturesController::class, 'index']);
Route::get('/recommended-ages', [Recommended_ageController::class, 'index']);

// ✅ 依頼関連
Route::post('/request', [Cause_Connect_CaseController::class, 'stores']);
Route::get('/posts', [Cause_Connect_CaseController::class, 'posts']);
Route::get('/search-posts', [Cause_Connect_CaseController::class, 'index']);
Route::get('/search-posts/{case_id}', [Cause_Connect_CaseController::class, 'show']);
Route::put('/case/{case_id}/update-state', [Cause_Connect_CaseController::class, 'updateState']);

// ✅ 画像関連
Route::post('/images/upload', [ImageUploadController::class, 'store']);
Route::get('/images/{case_id}/{picture_type}', [ImageUploadController::class, 'show']);
Route::middleware('auth:sanctum')->post('/content/upload', [ImageUploadController::class, 'upload']);

// ✅ レポート関連
Route::post('/request-report', [RequestReportController::class, 'store']);
Route::get('/request-report/{case_id}', [RequestReportController::class, 'show']);

// ✅ 出資・実行者関連
Route::post('/sup/update-or-create', [SupController::class, 'updateOrCreate']);
Route::post('/act', [ActController::class, 'join']);
Route::get('/cases/{case_id}/executors', [ActController::class, 'getExecutorIds']);

// ✅ 参加者情報取得
Route::prefix('cases')->group(function () {
    Route::get('/{case_id}/requester', [ParticipantsController::class, 'getRequester']);
    Route::get('/{case_id}/contributors', [ParticipantsController::class, 'getContributors']);
    Route::get('/{case_id}/executors', [ParticipantsController::class, 'getExecutors']);
});
