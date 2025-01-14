<?php

namespace App\Http\Controllers;

use App\Models\Request as RequestModel; // Requestモデルをインポート
use App\Models\Sup;
use App\Models\Act;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  // DBクラスをインポート
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class Cause_Connect_CaseController extends Controller
{
    public function stores(Request $request)
    {
        Log::info('Generated  request: ' . $request);
        \Log::info('Received exec_date:', ['exec_date' => $request->exec_date]);

        // バリデーション
        $validated = $request->validate([
        //     'exec_date' => 'required|date', // 日付形式であることを確認
        //     'client_id' => 'required|exists:users,id', // ユーザーIDが存在するか
        //     'case_name' => 'required|string|max:255',
        //     'achieve' => 'required|string|max:255',
        //     'lower_limit' => 'required|integer|min:1',
        //     'upper_limit' => 'required|integer|min:1',
        //     'case_date' => 'required|date',
        //     'start_activty' => 'required|integer|min:0|max:23',
        //     'end_activty' => 'required|integer|min:0|max:23',
        //     'address_id' => 'required|exists:addresses,id', // 住所IDが存在するか
        //     'equipment' => 'nullable|string|max:255',
        //     'area_id' => 'required|exists:activity_areas,id',
        //     'theme_id' => 'required|exists:activity_themes,id',
        //     'rec_age_id' => 'required|exists:recommended_ages,id',
        //     'feature_id' => 'required|exists:features,id',
        //     'area_detail' => 'nullable|string',
        //     'content' => 'nullable|string',
        //     'contents' => 'nullable|string',
        //     'state_id' => 'required|integer|exists:states,id', // 状態IDが存在するか
        ]);

        //トランザクションを開始
        DB::beginTransaction();

        try {
            // 住所情報を登録
            $address = Address::create([
                'pref_id' => $request->pref_id,      //都道府県ID
                'address1' => $request->address1,    //住所1
                'address2' => $request->address2,    //住所2
            ]);

            //登録した住所IDを取得
            $addressId = $address->address_id;

            //ログに住所IDを取得
            Log::info('Generated Address ID: ' . $addressId);

            //アドレスIDがnullか判別
            if (is_null($addressId)) {
                Log::error('Address ID is null.');
            }


            Log::info('Generated participation_id: ' . $request->participation_id);

            // 依頼情報を登録
            $case = RequestModel::create([
                'client_id' => $request->client_id, //依頼者ID
                'case_name' => $request->case_name, //依頼名
                'lower_limit' => $request->lower_limit, //下限人数
                'upper_limit' => $request->upper_limit, //上限人数
                'exec_date' => now(), // 依頼投稿日
                'start_activty' => $request->start_activty, // 活動開始時間
                'end_activty' => $request->end_activty, // 活動終了時間
                'address_id' => $addressId, // 住所ID
                'equipment' => $request->equipment, //　必要備品
                'area_id' => $request->area_id, // 活動エリアID
                'theme_id' => $request->theme_id, // 活動テーマID
                'rec_age_id' => $request->rec_age_id, // 推奨年齢ID
                //依頼者が参加か？
                'feature_id' => $request->feature_id, // 特徴ID
                'achieve' => $request->achieve, //依頼達成条件
                'area_detail' => $request->area_detail, // エリア詳細
                'content' => $request->content, // 内容(基本情報)
                'contents' => $request->contents, // 内容(依頼詳細)
                'google_map' => $request->google_map, //追加 googleマップのURL
                'case_date' => $request->exec_date, //活動日
                'state_id' => $request->state_id, // 進捗状況ID
                'num_people' => $request->participation_id, // 初期値を設定 現在参加人数
            ]);

            Log::info('Generated Case : ' . $case);


            $case_Id = $case->id;

            Log::info('Generated Case ID: ' . $case_Id);


            Sup::create([
                'user_id' => $request->client_id,
                'case_id' => $case_Id,
                'sup_point' => $request->sup_point,
            ]);

           if($request->participation_id == 1){
            Act::create([
                'user_id' => $request->client_id,
                'case_id' => $case_Id,
                'leader' => 1,
            ]);

           }

            // $filePath = null;

            // Content::create([
            //     'case_id' => $case_Id,

            // ]);

            // トランザクションをコミット
            DB::commit();

        } catch (\Exception $e) {
            // エラー発生時はトランザクションをロールバック(保存を取り消し)
            DB::rollBack();
            Log::error($e->getMessage());                   //エラーログを記録
            return response()->json(['error' => 'Failed to save user'], 500);
        }

        // 処理が成功した場合のレスポンス
        return response()->json(['message' => '依頼が正常に投稿されました'], 201);
    }

    public function posts()
    {
        try {
            $Ans = RequestModel::join('sup', 'case.case_id', '=', 'sup.case_id') // 全データを取得
            ->select('case.case_id', 'case.case_name','case.content','sup.sup_point')
            ->get();
            \Log::info('Fetched Ans:', ['data' => $Ans->toArray()]); // データをログに出力
            $Ans = $Ans->toArray();
            return response()->json($Ans); // JSONレスポンスを返す
        } catch (\Exception $e) {
            \Log::error('Error fetching feature:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch Ans'], 500);
        }
    }


    public function index(Request $request)
    {
        \Log::info('Fetched request:'.$request); // データをログに出力
        // 検索条件を取得
        $prefecture = $request->input('prefecture_id');
        $area = $request->input('area_id');
        $status = $request->input('status');
        $status = intval($status);  // $status を整数に変換
        $day = now();
        // 検索ロジックの記述
        $query = RequestModel::query();
        // 'address'テーブルのJOINを条件に応じてまとめて実行
        $query->leftJoin('address', 'case.address_id', '=', 'address.address_id');
        // エリアと募集状態による絞り込み
        if ($area) {
            $query->where('area_id', $area);
        }
        if ($status === 1) {
            // 募集中（exedayが現在の日付より後）
            $query->where('case_date', '>', $day);
        } elseif ($status === 2) {
            // 終了（exedayが現在の日付以前）
            $query->where('case_date', '<=', $day);
        }
        // 住所の絞り込み（prefecture）
        // $prefectureが指定されている場合は絞り込みを追加
        if ($prefecture) {
            $query->where('address.pref_id', $prefecture);
        }
        $posts = $query->get();
        \Log::info('Fetched posts:'.$posts); // データをログに出力
        return response()->json($posts);
    }
    public function show(Request $request,$id)
    {
        \Log::info('Fetched qwertyuio:'.$request); // データをログに出力
        try {
            \Log::info('Fetched id:',['id' => $id]); // データをログに出力
            $Ans = RequestModel::leftJoin('address', 'case.address_id', '=', 'address.address_id')
            ->where('case_id', '=', $id)
            ->get();
            \Log::info('Fetched Ans:', ['data' => $Ans->toArray()]); // データをログに出力
            $Ans = $Ans->toArray();
            return response()->json($Ans); // JSONレスポンスを返す
        } catch (\Exception $e) {
            \Log::error('Error fetching feature:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch Ans'], 500);
        }

    }
}
