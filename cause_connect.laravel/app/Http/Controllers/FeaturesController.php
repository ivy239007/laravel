<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Features;

class FeaturesController extends Controller
{
    public function index()
    {
        try {
            $feature = Features::all(); // 全データを取得
            \Log::info('Fetched feature:', ['data' => $feature->toArray()]); // データをログに出力
            return response()->json($feature); // JSONレスポンスを返す
        } catch (\Exception $e) {
            \Log::error('Error fetching feature:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch feature'], 500);
        }
    }
}
