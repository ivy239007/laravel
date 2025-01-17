<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sup;
use Illuminate\Support\Facades\Log;

class SupController extends Controller
{
    // ✅ 出資登録のみ（更新なし）
    public function store(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'user_id'   => 'required|integer',
            'case_id'   => 'required|integer',
            'sup_point' => 'required|integer|min:100',
        ]);

        try {
            Log::info('出資リクエスト受信', $validated);

            // ✅ 同じuser_idとcase_idが存在するか確認
            $exists = Sup::where('user_id', $validated['user_id'])
                         ->where('case_id', $validated['case_id'])
                         ->exists();

            if ($exists) {
                // ✅ 重複エラーを返す
                return response()->json([
                    'message' => 'すでにこの依頼に出資しています。'
                ], 409);
            }

            // ✅ 重複がなければ新規登録
            Sup::create($validated);

            Log::info('新規出資を登録しました', $validated);

            return response()->json([
                'message' => '出資が完了しました！'
            ], 201);

        } catch (\Exception $e) {
            Log::error('出資登録に失敗', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => '出資登録に失敗しました。'
            ], 500);
        }
    }
}
