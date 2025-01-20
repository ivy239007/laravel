<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParticipantsController extends Controller
{
    // ✅ 依頼者の取得（nicknameで表示）
    public function getRequester($case_id)
    {
        $requester = DB::table('case')
            ->join('user', 'case.client_id', '=', 'user.user_id')
            ->where('case.case_id', $case_id)
            ->select('user.user_id', 'user.nickname')  // ✅ nicknameで取得
            ->first();

        if ($requester) {
            return response()->json($requester);
        } else {
            return response()->json(['message' => '依頼者が見つかりません'], 404);
        }
    }

    // ✅ 出資者の取得（nicknameで表示）
    public function getContributors($case_id)
    {
        $contributors = DB::table('sup')
            ->join('user', 'sup.user_id', '=', 'user.user_id')
            ->where('sup.case_id', $case_id)
            ->select('user.user_id', 'user.nickname', 'sup.sup_point')
            ->get();

        return response()->json($contributors);
    }

    // ✅ 実行者の取得（nicknameで表示）
    public function getExecutors($case_id)
    {
        $executors = DB::table('act')
            ->join('user', 'act.user_id', '=', 'user.user_id')
            ->where('act.case_id', $case_id)
            ->select('user.user_id', 'user.nickname', 'act.leader')
            ->get();

        return response()->json($executors);
    }
}
