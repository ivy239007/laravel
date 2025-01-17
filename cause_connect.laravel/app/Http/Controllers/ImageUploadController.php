<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageUploadController extends Controller
{
    /**
     * 画像のアップロード
     */
    public function store(Request $request)
    {
        // バリデーション
        $validatedData = $request->validate([
            'case_id' => 'required|integer|exists:case,case_id',
            'pictures.*.picture_type' => 'required|integer',
            'pictures.*.picture' => 'required|image|max:2048',  // 最大2MB
        ]);

        // 画像の保存処理
        if ($request->hasFile('pictures')) {
            foreach ($request->file('pictures') as $picture) {
                $type = $picture['picture_type'];

                // 画像を保存（public/storage/uploads/photos）
                $path = $picture['picture']->store('uploads/photos', 'public');

                // データベースに保存
                Content::updateOrCreate(
                    ['case_id' => $validatedData['case_id'], 'picture_type' => $type],
                    ['picture' => $path]
                );

                Log::info('画像を保存しました:', [
                    'case_id' => $validatedData['case_id'],
                    'picture_type' => $type,
                    'path' => $path,
                ]);
            }
        }

        return response()->json(['message' => '画像が正常に保存されました'], 201);
    }

    /**
     * 画像の取得
     */
    public function show($case_id, $picture_type)
    {
        Log::info('画像取得開始:', [
            'case_id' => $case_id,
            'picture_type' => $picture_type
        ]);

        try {
            // データベースから画像パスを取得
            $image = Content::where('case_id', $case_id)
                            ->where('picture_type', $picture_type)
                            ->first();

            if (!$image) {
                return response()->json(['message' => '画像が見つかりません'], 404);
            }

            // 画像URLを生成して返却
            $imagePath = asset('storage/' . $image->picture);

            return response()->json(['picture' => $imagePath], 200);
        } catch (\Exception $e) {
            Log::error('画像取得エラー:', [
                'case_id' => $case_id,
                'picture_type' => $picture_type,
                'error' => $e->getMessage()
            ]);
            return response()->json(['message' => '画像の取得に失敗しました'], 500);
        }
    }
}
