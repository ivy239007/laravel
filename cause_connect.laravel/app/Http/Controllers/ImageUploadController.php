<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Content;

class ImageUploadController extends Controller
{
    public function store(Request $request)
    {
        \Log::info('ファイルアップロード処理開始'); // ログ記録

        $request->validate([
            'case_id' => 'required|integer',
            'picture_type' => 'required|integer',
            'picture' => 'required|image|max:2048',
        ]);

        \Log::info('リクエストデータ', $request->all()); // リクエスト内容をログに記録

        try {
            // 画像を保存する
            $path = $request->file('picture')->store('uploads', 'public');
            \Log::info('ファイル保存成功: ' . $path); // 保存されたパスをログに記録

            // データベースに保存
            $content = Content::create([
                'case_id' => $request->case_id,
                'picture_type' => $request->picture_type,
                'picture' => $path,
            ]);
            \Log::info('データベース登録成功', ['content' => $content]); // 保存されたレコードを記録

            return response()->json([
                'message' => 'Image uploaded successfully',
                'path' => $path,
            ], 201);
        } catch (\Exception $e) {
            // エラーログを記録
            \Log::error('ファイルアップロードエラー: ' . $e->getMessage());

            return response()->json([
                'message' => 'ファイルアップロード中にエラーが発生しました',
            ], 500);
        }
    }
}
