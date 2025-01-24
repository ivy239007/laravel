<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Favorite;
use App\Models\User;
use App\Models\Address;
use App\Models\Content;
use Illuminate\Http\Request;
class FavoriteController extends Controller
{
    public function store(Request $request)
    {

        Log::info('request', [$request]);
        // バリデーション
        $validatedData = $request->validate([
            'case_id' => 'required|integer',
            'user_id' => 'required|integer',
            'is_favorite' => 'required|boolean',
        ]);
        Log::info('Validated Data:', [$validatedData]);
        // お気に入りデータを保存
        $favorite = Favorite::updateOrCreateCustom(
            ['case_id' => $validatedData['case_id'], 'user_id' => $validatedData['user_id']],
            ['favo_flg' => (bool) $validatedData['is_favorite']]
        );

        return response()->json([
            'success' => true,
            'favorite' => $favorite,
        ]);
    }

    public function index(Request $request)
    {
        Log::info('requestお気に入り:', [$request->user_id]);

        if (!$request->has('user_id')) {
            Log::error('リクエストに user_id が含まれていません。');
            return response()->json(['error' => 'user_id is missing'], 400);
        }

        // ユーザーを取得
        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // お気に入り情報から favo_flg = 1 の case_id を取得し、関連する依頼情報を取得
        $favoriteCases = $user->favorites()
            ->where('favo_flg', 1)
            ->with([
                'case' => function ($query) {
                    // case テーブルと address テーブルを JOIN
                    $query->join('address', 'case.address_id', '=', 'address.address_id')->select('case.*', 'address.*'); // 必要な列を選択
                }
            ])->get();
        foreach ($favoriteCases as $favorite) {
            $case = $favorite->case;
            $caseId = $case->case_id; // 各レコードの case_id を取得
            Log::info('画像取得開始:', ['case_id' => $caseId, 'picture_type' => 1,]);
            try {
                // 画像を取得
                $image = Content::where('case_id', $caseId)
                    ->where('picture_type', 1) // 画像タイプを指定
                    ->first();
                if ($image) {
                    // URLを生成してレコードに追加
                    $case->picture = asset('storage/' . $image->picture);
                } else {
                    Log::warning('画像が見つかりません:', ['case_id' => $caseId]);
                    $case->picture = null; // デフォルト画像を設定可能
                }
            } catch (\Exception $e) {
                Log::error('画像取得エラー:', [
                    'case_id' => $caseId,
                    'error' => $e->getMessage()
                ]);
                $case->picture = null; // エラー時は null を設定
            }
            $cases = $favoriteCases->map(function ($favorite) {
                $case = $favorite->case;
                // 必要なフィールドだけをまとめた配列を返す
                return
                    [
                        'case_id' => $case->case_id,
                        'case_name' => $case->case_name,
                        'case_date' => $case->case_date,
                        'content' => $case->content,
                        'address' =>
                            [
                                'address1' => $case->address1,
                                'address2' => $case->address2,
                                'pref_id' => $case->pref_id
                            ],
                        'picture' => $case->picture // 追加で画像があれば
                    ];
            });
        }
        Log::info('$favoriteCases:', [$cases]);
        return response()->json($cases);
    }
    public function checkFavorite(Request $request)
    {
        $validatedData = $request->validate([
            'case_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        $isFavorite = Favorite::where('case_id', $validatedData['case_id'])
            ->where('user_id', $validatedData['user_id'])
            ->where('favo_flg', 1)
            ->exists();

        return response()->json(['is_favorite' => $isFavorite]);
    }

}
