<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    // チャット履歴取得
    public function index($case_id)
    {
        try {
            // チャットデータを user テーブルと JOIN
            $messages = DB::table('chat')
                ->join('user', 'chat.user_id', '=', 'user.user_id') // user テーブルを JOIN
                ->select(
                    'chat.created', // チャットの送信日時
                    'chat.case_id', // 依頼ID
                    'chat.user_id', // ユーザID
                    'chat.message', // メッセージ本文
                    'user.nickname' // ユーザーのニックネーム
                )
                ->where('chat.case_id', $case_id) // 指定された case_id のみ取得
                ->orderBy('chat.created', 'asc') // メッセージを送信順に並べる
                ->get();

            return response()->json([
                'success' => true,
                'messages' => $messages
            ], 200);
        } catch (\Exception $e) {
            Log::error('チャット履歴取得エラー:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'チャット履歴の取得に失敗しました。'
            ], 500);
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
            DB::beginTransaction(); // トランザクション開始

            // メッセージを保存
            $chat = Chat::create($validated);

            // 保存したメッセージと関連するユーザーのニックネームを取得
            $savedMessage = DB::table('chat')
                ->join('user', 'chat.user_id', '=', 'user.user_id')
                ->select(
                    'chat.created',
                    'chat.case_id',
                    'chat.user_id',
                    'chat.message',
                    'user.nickname'
                )
                ->where('chat.id', $chat->id) // 新しく作成したメッセージを取得
                ->first();

            DB::commit(); // トランザクションをコミット

            return response()->json(['message' => $savedMessage], 201);
        } catch (\Exception $e) {
            DB::rollBack(); // トランザクションをロールバック
            Log::error('メッセージ送信エラー:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'メッセージの送信に失敗しました'], 500);
        }
    }
}
