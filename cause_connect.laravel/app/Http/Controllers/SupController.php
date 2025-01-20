<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sup;
use Illuminate\Support\Facades\Log;

class SupController extends Controller
{
    // ✅ 出資登録または更新
    public function updateOrCreate(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'user_id'   => 'required|integer',
            'case_id'   => 'required|integer',
            'sup_point' => 'required|integer|min:100',
        ]);

        try {
            Log::info('出資リクエスト受信', $validated);

            // ✅ `updateOrCreate` を使ってデータを登録または更新
            $sup = Sup::updateOrCreate(
                [
                    'user_id' => $validated['user_id'], // 条件
                    'case_id' => $validated['case_id'], // 条件
                ],
                [
                    'sup_point' => $validated['sup_point'], // 更新内容
                ]
            );

            Log::info('出資データを登録または更新しました', $sup->toArray());

            return response()->json([
                'message' => '出資登録が完了しました！',
                'data' => $sup,
            ], 201);

        } catch (\Exception $e) {
            Log::error('出資登録に失敗', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => '出資登録に失敗しました。',
            ], 500);
        }
    }
}
