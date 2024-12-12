<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity_theme;

class Activity_themeController extends Controller
{
    public function index()
    {
        try {
            $activity_theme = Actibity_theme::all(); // 全データを取得
            \Log::info('Fetched activity_theme:', ['data' => $activity_theme->toArray()]); // データをログに出力
            return response()->json($$activity_theme); // JSONレスポンスを返す
        } catch (\Exception $e) {
            \Log::error('Error fetching activity_theme:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch activity_theme'], 500);
        }
    }
}
