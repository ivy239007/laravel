<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Act;

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
}
