<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Act;

class ActController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'ユーザーが認証されていません。'], 401);
        }

        $validated = $request->validate([
            'case_id' => 'required|integer',
            'leader' => 'required|boolean',
        ]);

        Act::create([
            'user_id' => $user->id,
            'case_id' => $validated['case_id'],
            'leader' => $validated['leader'],
        ]);

        return response()->json(['message' => '実行者として参加しました。'], 201);
    }
}
