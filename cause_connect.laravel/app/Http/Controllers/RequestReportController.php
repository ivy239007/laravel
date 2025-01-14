<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Request_report; // コメント用モデル
use App\Models\Content; // 写真用モデル
use Illuminate\Support\Facades\Log; // ログ用のファサードをインポート

class RequestReportController extends Controller
{
    /**
     * 依頼報告を保存または更新する
     */
    public function store(Request $request)
    {
        // バリデーションを先に行う
        $validatedData = $request->validate([
            'case_id' => 'required|integer',
            'comment1' => 'nullable|string|max:200',
            'comment2' => 'nullable|string|max:200',
            'comment3' => 'nullable|string|max:200',
            'comment4' => 'nullable|string|max:200',
        ]);

        // リクエストデータをログに出力
        Log::info('Request Data:', $request->all());

        // 写真が送信されているか確認
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $type => $file) {
                try {
                    // ファイルを保存
                    $path = $file->store('uploads/photos', 'public');
                    Log::info("Saved photo with type {$type} at: {$path}");
                    Log::info('DB operation successful:', ['case_id' => $validatedData['case_id'], 'picture_type' => $type]);

                    // 更新または挿入
                    Content::updateOrCreate(
                        ['case_id' => $validatedData['case_id'], 'picture_type' => $type],
                        ['picture' => $path]
                    );
                    $updatedContent = Content::where('case_id', $validatedData['case_id'])
                        ->where('picture_type', $type)
                        ->first();

                    if ($updatedContent) {
                        Log::info('Content updated or created successfully:', $updatedContent->toArray());
                    } else {
                        Log::error('Content updateOrCreate failed:', [
                            'case_id' => $validatedData['case_id'],
                            'picture_type' => $type,
                            'picture' => $path,
                        ]);
                    }


                    Log::info('File saved to path:', ['path' => $path]);

                    Log::info('Inserted or updated content record:', [
                        'case_id' => $validatedData['case_id'],
                        'picture_type' => $type,
                        'picture' => $path,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error saving photo:', [
                        'case_id' => $validatedData['case_id'],
                        'picture_type' => $type,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        try {
            // コメントデータの保存または更新
            $existingReport = Request_report::updateOrCreate(
                ['case_id' => $validatedData['case_id']], // 検索条件
                [
                    'comment1' => $validatedData['comment1'],
                    'comment2' => $validatedData['comment2'],
                    'comment3' => $validatedData['comment3'],
                    'comment4' => $validatedData['comment4'],
                ]
            );

            Log::info('Report successfully saved or updated:', [
                'case_id' => $validatedData['case_id'],
                'data' => $existingReport,
            ]);

            return response()->json(['message' => '依頼報告を保存または更新しました', 'data' => $existingReport], 200);
        } catch (\Exception $e) {
            // エラー発生時のログ出力
            Log::error('Error saving report or photos:', ['error' => $e->getMessage()]);
            return response()->json(['message' => '依頼報告の保存に失敗しました'], 500);
        }
    }

    /**
     * 指定されたcase_idに基づく依頼報告を取得する
     */
    public function show($case_id)
    {
        // 指定されたcase_idのログ出力
        Log::info('Fetching report for case_id:', ['case_id' => $case_id]);

        try {
            // データ取得
            $report = Request_report::where('case_id', $case_id)->first();
            $photos = Content::where('case_id', $case_id)->get();

            if (!$report) {
                // データが見つからない場合のログ出力
                Log::warning('Report not found for case_id:', ['case_id' => $case_id]);
                return response()->json(['message' => 'データが見つかりません', 'case_id' => $case_id], 404);
            }

            // データ取得成功時のログ出力
            Log::info('Report and photos found:', ['report' => $report, 'photos' => $photos]);

            return response()->json(['report' => $report, 'photos' => $photos], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching report:', [
                'case_id' => $case_id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'データ取得に失敗しました'], 500);
        }
    }
}
