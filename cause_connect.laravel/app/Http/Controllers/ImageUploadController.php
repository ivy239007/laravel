<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\Request_report; // モデルのインポート

class ImageUploadController extends Controller
{
    public function store(Request $request)
    {
        // 入力データのバリデーション
        $validatedData = $request->validate([
            'case_id' => 'required|integer|exists:case,case_id', // 存在する依頼IDか確認
            'pictures.*.picture_type' => 'required|integer', // 複数画像の区分
            'pictures.*.picture' => 'required|image|max:2048', // 各画像のバリデーション
            'comment1' => 'nullable|string|max:200', // 参加者管理コメント
            'comment2' => 'nullable|string|max:200', // 依頼場所コメント
            'comment3' => 'nullable|string|max:200', // 実行前コメント
            'comment4' => 'nullable|string|max:200', // 実行後コメント
        ]);

        // コメントデータの保存または更新
        $requestReport = Request_report::updateOrCreate(
            ['case_id' => $validatedData['case_id']], // 検索条件
            [
                'comment1' => $validatedData['comment1'] ?? null,
                'comment2' => $validatedData['comment2'] ?? null,
                'comment3' => $validatedData['comment3'] ?? null,
                'comment4' => $validatedData['comment4'] ?? null,
            ]
        );

        // 画像データの保存処理
        if ($request->has('pictures')) {
            foreach ($request->pictures as $picture) {
                // 画像の保存
                $imageContent = file_get_contents($picture['picture']->getRealPath());

                Content::create([
                    'case_id' => $validatedData['case_id'],
                    'picture_type' => $picture['picture_type'],
                    'picture' => $imageContent,
                ]);
            }
        }

        // レスポンスを返す
        return response()->json([
            'message' => 'データを正常に保存または更新しました',
            'request_report' => $requestReport,
        ], 201);
    }
}
