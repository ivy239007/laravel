<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\State;

class StateController extends Controller
{
    public function index()
    {
        try {
            $state = State::all(); // 全データを取得
            \Log::info('Fetched state:', ['data' => $state->toArray()]); // データをログに出力
            return response()->json($state); // JSONレスポンスを返す
        } catch (\Exception $e) {
            \Log::error('Error fetching state:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch state'], 500);
        }
    }

}
