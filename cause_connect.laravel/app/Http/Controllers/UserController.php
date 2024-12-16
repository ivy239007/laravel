<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * アイコンのアップロード
     */
    public function updateIcon(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('icon')) {
            $icon = $request->file('icon');
            $binaryData = file_get_contents($icon->getRealPath());

            $user->icon = $binaryData; // バイナリデータを保存
            $user->save();

            return response()->json(['message' => 'アイコンが更新されました'], 200);
        }

        return response()->json(['error' => '画像が選択されていません'], 400);
    }

    /**
     * 認証ユーザーの情報を取得
     */
    public function getUser(Request $request)
    {
        $user = Auth::user();

        if ($user->icon) {
            // バイナリデータをBase64に変換して送信
            $user->icon = 'data:image/jpeg;base64,' . base64_encode($user->icon);
        }

        return response()->json(['user' => $user]);
    }
}
