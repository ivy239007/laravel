<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Address;
use App\Models\Prefectures;
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
            Log::info('Generated Address ID: ' . $addressId);

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

            Log::warning('Login failed', [
                'email' => $request->email,
                'time' => now(),
            ]);

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

        Log::info('authenticatedUser: ' . $authenticatedUser);

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
        Log::info('Response User Data:', ['user' => $user]);

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
}


//     case_id: post.case_id,        // case_id を取得
//     case_name: post.case_name,    // case_name を取得
//     content: post.content,        // content を取得
//     picture: post.picture,        // picture を取得
//     sup_point: Number(post.sup_point),  // sup_point を数値に変換
