<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recommended_age;

class Recommended_ageController extends Controller
{
    public function index()
    {
        try {
            $recommended_age = Recommended_age::all(); // 全データを取得
            \Log::info('Fetched recommended_age:', ['data' => $recommended_age->toArray()]); // データをログに出力
            return response()->json($recommended_age); // JSONレスポンスを返す
        } catch (\Exception $e) {
            \Log::error('Error fetching recommended_age:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch recommended_age'], 500);
        }
    }

}
