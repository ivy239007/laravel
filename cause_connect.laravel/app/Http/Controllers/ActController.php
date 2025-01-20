<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\User;

class ActController extends Controller
{
    // 実行者として参加する処理
    public function join(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'case_id' => 'required|integer',
        ]);

        // 重複チェック
        $exists = Act::where('user_id', $validated['user_id'])
            ->where('case_id', $validated['case_id'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'すでに参加済みです。'], 409);
        }

        // 実行者として登録
        Act::create([
            'user_id' => $validated['user_id'],
            'case_id' => $validated['case_id'],
            'leader' => 0,
        ]);

        return response()->json(['message' => '実行者として参加しました！'], 201);
    }
    public function getExecutorIds($case_id)
    {
        try {
            // ✅ actテーブルからcase_idに紐づくuser_idを取得
            $executors = Act::where('case_id', $case_id)->pluck('user_id');
    
            if ($executors->isEmpty()) {
                return response()->json(['message' => '実行者が見つかりませんでした。'], 404);
            }
    
            return response()->json($executors, 200);
        } catch (\Exception $e) {
            \Log::error('実行者IDの取得に失敗:', ['error' => $e->getMessage()]);
            return response()->json(['error' => '実行者IDの取得に失敗しました。'], 500);
        }
    }

}
