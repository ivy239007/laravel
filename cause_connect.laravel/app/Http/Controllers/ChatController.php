<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    // チャット履歴取得
    public function index($case_id)
    {
        try {
            $messages = Chat::where('case_id', $case_id)
                ->orderBy('created', 'asc') // 日時順にソート
                ->get();

            return response()->json(['messages' => $messages], 200);
        } catch (\Exception $e) {
            Log::error('チャット履歴取得エラー:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'チャット履歴の取得に失敗しました'], 500);
        }
    }

    // 新しいメッセージを送信
    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_id' => 'required|integer',
            'user_id' => 'required|integer',
            'message' => 'required|string|max:100',
        ]);

        try {
            $chat = Chat::create($validated);
            return response()->json(['message' => $chat], 201);
        } catch (\Exception $e) {
            Log::error('メッセージ送信エラー:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'メッセージの送信に失敗しました'], 500);
        }
    }
    public function getMessages(Request $request, $caseId)
    {
        try {
            $messages = Chat::where('case_id', $caseId)
                ->orderBy('created', 'asc') // メッセージを時系列順に並べる
                ->get();

            return response()->json($messages, 200);
        } catch (\Exception $e) {
            \Log::error('メッセージ取得エラー: ' . $e->getMessage());
            return response()->json(['error' => 'メッセージ取得に失敗しました'], 500);
        }
    }

}
