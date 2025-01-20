<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache; // ✅ キャッシュを利用するためにインポート
use App\Models\Prefectures;

class PrefectureController extends Controller
{
    public function index()
    {
        // ✅ キャッシュを利用して都道府県データを取得
        $prefectures = Cache::remember('prefectures', 60 * 60, function () {
            return Prefectures::all(); // データベースから全件取得
        });

        \Log::info('Prefectures retrieved:', $prefectures->toArray()); // ログにデータを記録

        return response()->json($prefectures); // JSON形式でレスポンスを返す
    }
}
