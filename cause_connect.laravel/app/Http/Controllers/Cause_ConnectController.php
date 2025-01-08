<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Address;
use App\Models\Prefectures;
use App\Models\Request as RequestModel; // Requestモデルをインポート
use App\Models\Sup;
use App\Models\Content;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;  // DBクラスをインポート
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;


class Cause_ConnectController extends Controller
{
    /**
     * 新規ユーザー登録処理
     */
    public function store(Request $request)
    {
        // 入力データのバリデーション（現在はコメントアウト中）
        // 必要に応じて再度有効化可能
        // $request->validate([
        //     'password' => 'required|string|min:8',                            //パスワード(8文字以上)
        //     'nickname' => 'required|string|max:20',                           //ニックネーム(20文字以内)　　　
        //     'name' => 'required|string|max:10',                              //名前(10文字以内)
        //     'kana' => 'required|string|max:10',                              //カナ表記(10文字以内)
        //     'birth' => 'required|date',                                      //生年月日
        //     'sex' => 'required|string|max:3',                                //性別(3文字以内)　　
        //     'tel' => 'required|string|max:11',                               //電話番号(11文字以内)
        //     'email' => 'required|string|email|max:100|unique:user,email',    //メールアドレス(ユニーク)
        //     'address1' => 'required|string|max:20',                          //住所1(20文字以内)
        //     'address2' => 'nullable|string|max:100',                         //住所2(100文字以内、任意)
        //     'post_code' => 'required|string|max:10',                         //郵便番号(10文字以内)
        //     'pref_id' => 'required|exists:prefectures,pref_id',              //都道府県ID(外部キー制約)
        //     'intro' => 'nullable|string|max:500',                            //自己紹介(500文字以内、任意)
        // ]);

        //トランザクションを開始
        DB::beginTransaction();

        try {

            // 住所情報を登録
            $address = Address::create([
                'pref_id' => $request->pref_id,      //都道府県ID
                'address1' => $request->address1,    //住所1
                'address2' => $request->address2,    //住所2
                'post_code' => $request->post_code,  //郵便番号
            ]);

            //登録した住所IDを取得
            $addressId = $address->address_id;
            // 画像の保存
            $photo1Path = $request->hasFile('photo1') ? $request->file('photo1')->store('uploads', 'public') : null;
            $photo2Path = $request->hasFile('photo2') ? $request->file('photo2')->store('uploads', 'public') : null;

            //ログに住所IDを取得
            // Log::info('Generated Address ID: ' . $addressId);

            //アドレスIDがnullか判別
            if (is_null($addressId)) {
                Log::error('Address ID is null.');
            }

            $filePath = null;

            // ユーザー情報を登録
            User::create([
                'password' => $request->password, //パスワード
                'nickname' => $request->nickname, //ニックネーム
                'name' => $request->name,         //名前
                'kana' => $request->kana,         //カナ表記
                'birth' => $request->birth,       //生年月日
                'sex' => $request->sex,           //性別
                'tel' => $request->tel,           //電話番号
                'email' => $request->email,       //メールアドレス
                'address_id' => $addressId,       //登録した住所ID
                'intro' => $request->intro,       //自己紹介
                'icon' => $filePath,              // 画像のパス（nullも可）
                'photo1' => $photo1Path,
                'photo2' => $photo2Path,
            ]);

            // トランザクションをコミット
            DB::commit();
            // 処理が成功した場合のレスポンス
            return response()->json([
                'message' => '依頼が正常に投稿されました',
                'photo1_path' => $photo1Path,
                'photo2_path' => $photo2Path,
            ], 201);

        } catch (\Exception $e) {
            // エラー発生時はトランザクションをロールバック(保存を取り消し)
            DB::rollBack();
            Log::error($e->getMessage());                   //エラーログを記録
            return response()->json(['error' => 'Failed to save user'], 500);
        }


    }

    // ログイン処理
    public function login(Request $request)
    {
        try {
            // バリデーション：メールアドレスとパスワードが必須
            // $request->validate([
            //     'email' => 'required|email', // メールアドレスが必須かつ有効な形式
            //     'password' => 'required',    // パスワードが必須
            // ]);

            // ユーザーをメールアドレスで検索
            $user = User::where('email', $request->email)->first();

            Log::info('Login failed', [
                'user_email' => $user->email,
                'user_password' => $user->password,
                '$request_email' => $request->email,
                '$request_password' => $request->password,
            ]);

            // メールアドレスとパスワードが一致する場合
            if ($request->email == $user->email && $request->password == $user->password) {

                // トークンの生成(Sanctum を使用)
                $token = $user->createToken('Personal Access Token')->plainTextToken;

                // ログイン成功のレスポンス
                return response()->json([
                    'message' => 'Login successful', // 成功メッセージ
                    'token' => $token,               // トークンを返す
                ]);
            }

            // Log::warning('Login failed', [
            //     'email' => $request->email,
            //     'time' => now(),
            // ]);

            // ログイン失敗の場合 (認証エラー)
            return response()->json(['message' => 'Invalid credentials'], 401);

        } catch (\Exception $e) {
            // エラーをログに記録
            Log::error('Login error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    //ユーザー情報取得
    public function getUser(Request $request)
    {
        // 現在認証されているユーザーの情報を取得し、JSONで返す
        return response()->json(['user' => Auth::user()]);
    }

    // ログアウト処理
    public function logout(Request $request)
    {
        // 現在認証中のユーザーが持つトークンを削除
        $request->user()->tokens->each(function ($token) {
            $token->delete(); // トークンを削除
        });

        // ログアウト成功のレスポンス
        return response()->json(['message' => 'Logged out']);
    }

    public function me(Request $request)
    {
        // リクエストに紐づく認証済みユーザーを取得
        $authenticatedUser = $request->user();

        // Log::info('authenticatedUser: ' . $authenticatedUser);

        // データベースのユーザー情報を再取得
        $user = User::with('address.prefectures') // addressとprefecture情報も一緒に取得
            ->where('user_id', $authenticatedUser->user_id)
            ->first();

        Log::info('user' . $user);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        // アイコンのフルURLを生成
        $user->icon = $user->icon
            ? (str_starts_with($user->icon, '/storage/')
                ? asset($user->icon)
                : asset('storage/' . $user->icon))
            : asset('storage/icons/default-avatar.png');
        // レスポンス直前にログ出力
        // Log::info('Response User Data:', ['user' => $user]);

        // 照合後のユーザー情報を返す
        return response()->json($user);
    }

    public function update(Request $request)
    {

        $user = $request->user(); // 認証中のユーザーを取得

        // バリデーションルール
        // $validator = Validator::make($request->all(), [
        //     'nickname' => 'required|string|max:50',
        //     'name' => 'required|string|max:100',
        //     'kana' => 'required|string|max:100',
        //     'birth' => 'nullable|date',
        //     'sex' => 'nullable|string|in:男性,女性,その他',
        //     'tel' => 'nullable|string|max:20',
        //     'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        //     'address.prefectures' => 'nullable|string|max:50',
        //     'address.address1' => 'nullable|string|max:255',
        //     'address.address2' => 'nullable|string|max:255',
        //     'address.post_code' => 'nullable|string|max:10',
        //     'intro' => 'nullable|string|max:500',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422);
        // }

        Log::info('Request Data:' . $user);
        Log::info('Request Data:' . $user->address);

        // 住所データの更新
        if ($user->address) {
            $user->address->update($request->input('address'));
            $user->address->pref_id = $request->input('address.prefectures.pref_id');
            $user->address->save();
        } else {
            $user->address()->create($request->input('address'));
        }

        // ユーザーデータの更新
        $user->fill($request->only([
            'nickname',
            'name',
            'kana',
            'birth',
            'sex',
            'tel',
            'email',
            'intro',
            'icon'
        ]));

        $user->save();

        return response()->json(['message' => 'ユーザー情報が更新されました。', 'user' => $user]);
    }

    public function destroy(Request $request)
    {
        $user = $request->user(); // 認証中のユーザーを取得

        // ユーザー削除
        $user->delete();

        return response()->json(['message' => 'アカウントが削除されました。']);
    }

    public function stores(Request $request)
    {

        Log::info('Generated  request: ' . $request);

        // バリデーション
        // $validated = $request->validate([
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
        // ]);

        //トランザクションを開始
        DB::beginTransaction();

        try {
            // 画像保存処理
            $photos = [];
            foreach (['photo1', 'photo2'] as $index => $photoKey) {
                if ($request->hasFile($photoKey)) {
                    $photos[] = [
                        'picture_type' => $index + 1, // 画像区分 (1: 基本情報, 2: 詳細情報など)
                        'path' => $request->file($photoKey)->store('uploads', 'public'),
                    ];
                }
            }
            // 住所情報を登録
            $address = Address::create([
                'pref_id' => $request->pref_id,      //都道府県ID
                'address1' => $request->address1,    //住所1
                'address2' => $request->address2,    //住所2
            ]);

            //登録した住所IDを取得
            $addressId = $address->address_id;

            //ログに住所IDを取得
            // Log::info('Generated Address ID: ' . $addressId);

            //アドレスIDがnullか判別
            if (is_null($addressId)) {
                Log::error('Address ID is null.');
            }


            Log::info('Saving to `case` Table:', $request->all()); // 保存直前にリクエスト内容をログ

            // 依頼情報を登録
            $case = RequestModel::create([
                'client_id' => $request->client_id, //依頼者ID
                'case_name' => $request->case_name, //依頼名
                'lower_limit' => $request->lower_limit, //下限人数
                'upper_limit' => $request->upper_limit, //上限人数
                'exec_date' => $request->exec_date, //活動日
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
                'case_date' => now(), //依頼投稿時間
                'state_id' => $request->state_id, // 進捗状況ID
                'num_people' => $request->participation_id, // 初期値を設定 現在参加人数
            ]);
            // contentテーブルに画像を保存
            foreach ($photos as $photo) {
                DB::table('content')->insert([
                    'case_id' => $case->id,
                    'picture_type' => $photo['picture_type'],
                    'picture' => $photo['path'],
                ]);
            }
            
            Log::info('Saved Case:', $case->toArray());
            Log::info('GoogleMap: ' . $request->google_map);
            // Log::info('Generated Case : ' . $case);

            // Log::info('Saving to `case` Table:', $request->all());

            $case_Id = $case->id;

            // Log::info('Generated Case ID: ' . $case_Id);


            Sup::create([
                'user_id' => $request->client_id,
                'case_id' => $case_Id,
                'sup_point' => $request->sup_point,
            ]);

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
        return response()->json(['message' => '依頼が正常に投稿されました', 'uploaded_photos' => $photos,], 201);
    }
}
