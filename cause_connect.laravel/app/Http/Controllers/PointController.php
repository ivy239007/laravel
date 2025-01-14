<?php

namespace App\Http\Controllers;

use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PointController extends Controller
{
    /**
     * ユーザーのポイント履歴を取得
     */
    public function getHistory(Request $request)
    {
        try {
            $user = Auth::user();

            \Log::info('認証ユーザー情報:', ['user' => $user]);

            if (!$user) {
                return response()->json(['message' => '認証されていません'], 401);
            }
            \Log::info('認証ユーザーの属性:', ['attributes' => $user->toArray()]);

            // デバッグログ追加
            \Log::info('クエリに使用するユーザーID:', ['user_id' => $user->user_id]);

            $history = Point::where('user_id', $user->user_id)
                ->orderBy('timestamp', 'desc')
                ->get();

            $currentPoints = $history->sum('points');

            \Log::info('取得したポイント履歴:', ['history' => $history]);
            \Log::info('現在のポイント:', ['current_points' => $currentPoints]);

            return response()->json([
                'current_points' => $currentPoints,
                'history' => $history,
            ]);
        } catch (\Exception $e) {
            \Log::error('ポイント履歴の取得に失敗', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'サーバーエラーが発生しました'], 500);
        }
    }

    /**
     * ポイントを購入する
     */
    public function purchasePoints(Request $request)
    {
        try {
            // 認証ユーザーの確認
            $user = Auth::user();

            // デバッグログ: ユーザー情報
            \Log::info('認証ユーザー情報:', ['user' => $user]);

            // ユーザーが認証されていない場合の処理
            if (!$user) {
                \Log::error('ユーザーが認証されていません');
                return response()->json(['message' => '認証されていません'], 401);
            }

            // デバッグログ: user_idの確認
            \Log::info('ユーザーIDの確認:', ['user_id' => $user->user_id]);

            // リクエストからポイント数を取得
            $points = $request->input('points');
            if (!$points || !is_numeric($points) || $points <= 0) {
                \Log::error('無効なポイント数:', ['points' => $points]);
                return response()->json(['message' => '無効なポイント数です'], 400);
            }

            \Log::info('ポイント検証成功:', ['points' => $points]);

            // ポイントをデータベースに保存
            Point::create([
                'user_id' => $user->user_id, // 正しいuser_idをここで使用
                'timestamp' => now(),
                'points' => $points,
                'description' => 'ポイント購入',
            ]);

            // 現在のポイント総数を計算
            $totalPoints = Point::where('user_id', $user->user_id)->sum('points');

            \Log::info('ポイント保存成功:', ['user_id' => $user->user_id, 'total_points' => $totalPoints]);

            return response()->json([
                'message' => 'ポイント購入が成功しました',
                'current_points' => $totalPoints,
            ]);
        } catch (\Exception $e) {
            \Log::error('ポイント購入処理エラー', ['exception' => $e->getMessage()]);
            return response()->json(['message' => 'サーバーエラーが発生しました'], 500);
        }
    }
}
