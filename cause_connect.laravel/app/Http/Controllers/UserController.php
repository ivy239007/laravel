<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * ユーザーアイコンのアップロード処理
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadIcon(Request $request)
    {
        // **1. バリデーション**
        // アップロードされたファイルが画像形式で、最大サイズ2MBであることを確認
        $request->validate([
            'icon' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        try {
            // **2. 現在のユーザー情報を取得**
            $user = auth()->user();

            // **3. 古いアイコンファイルの削除**
            // 以前のアイコンが存在する場合、ストレージから削除
            if ($user->icon && Storage::disk('public')->exists($user->icon)) {
                Storage::disk('public')->delete($user->icon);
            }

            // **4. 新しいアイコンファイルの保存**
            // ファイルを保存し、パスを取得（例: icons/filename.jpg）
            $filePath = $request->file('icon')->store('icons', 'public');

            // **5. ユーザー情報の更新**
            // 保存したファイルパスをユーザーの`icon`フィールドにセットして保存
            $user->icon = $filePath;
            $user->save();

            // **6. 成功レスポンスの返却**
            // 保存したパスをフルURL形式でフロントエンドに渡す
            return response()->json([
                'message' => 'アイコンがアップロードされました',
                'icon' => $filePath, // 相対パスのみ返却
            ], 200);
        } catch (\Exception $e) {
            // **7. エラーハンドリング**
            // エラー発生時にメッセージをログに記録し、クライアントにエラーを返却
            \Log::error('アイコンアップロード中にエラーが発生しました: ' . $e->getMessage());

            return response()->json([
                'message' => 'アイコンのアップロード中にエラーが発生しました',
            ], 500); // HTTPステータスコード500（サーバーエラー）
        }
    }
}
