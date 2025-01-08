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
            $user = Auth::user();
            // デバッグ用ログ
            \Log::info('認証ユーザー情報:', ['user' => $user]);
            // 認証されていない場合
            if (!$user) {
                return response()->json(['message' => '認証されていません'], 401);
            }

            // デバッグ用ログ
            \Log::info('購入ポイントリクエスト:', ['points' => $request->input('points')]);
            \Log::info('ポイント購入処理開始', ['user_id' => $user->id]);

            // 購入ポイントの検証
            $points = $request->input('points');
            if (!$points || !is_numeric($points) || $points <= 0) {
                return response()->json(['message' => '無効なポイント数です'], 400);
            }

            // ポイントをデータベースに保存
            Point::create([
                'user_id' => $user->id,
                'timestamp' => now(),
                'points' => $points,
                'description' => 'ポイント購入',
            ]);

            // ユーザーの現在のポイントを再計算
            $totalPoints = Point::where('user_id', $user->id)->sum('points');

            \Log::info('ポイント購入処理成功', ['user_id' => $user->id, 'points' => $points]);

            // 成功レスポンス
            return response()->json([
                'message' => 'ポイント購入が成功しました',
                'current_points' => $totalPoints,
            ]);
        } catch (\Exception $e) {
            // エラーの詳細をログに記録
            \Log::error('ポイント購入処理エラー', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'サーバーエラーが発生しました'], 500);
        }
    }
}
