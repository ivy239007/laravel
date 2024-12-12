<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Place;

class PlaceController extends Controller
{
    // 活動エリアのデータ取得
    public function index()
    {
        try {
            $place = Place::all(); // 全データを取得
            \Log::info('Fetched place:', ['data' => $place->toArray()]); // データをログに出力
            return response()->json($place); // JSONレスポンスを返す
        } catch (\Exception $e) {
            \Log::error('Error fetching place:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch place'], 500);
        }
    }
}
