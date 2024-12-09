<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Address;
use App\Models\Prefectures;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;  // DBクラスをインポート
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;


class Cause_ConnectController extends Controller
{
    public function store(Request $request)
    {
        //入力データのバリデーションチェック
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

        try
        {
            // 住所を登録
            $address = Address::create([
                'pref_id' => $request->pref_id,      //都道府県ID
                'address1' => $request->address1,    //住所1
                'address2' => $request->address2,    //住所2
                'post_code' => $request->post_code,  //郵便番号
            ]);

            //登録直後の住所IDを取得
            $addressId = $address->address_id;

            //取得したアドレスIDをログに表示
            Log::info('Generated Address ID: ' . $addressId);

            //アドレスIDがnullか判別
            if (is_null($addressId))
            {
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
            ]);

            // トランザクションをコミット
            DB::commit();

        }
        catch(\Exception $e)
        {
            // エラー発生時はトランザクションをロールバック(保存を取り消し)
            DB::rollBack();
            Log::error($e->getMessage());                   //エラーログを記録
            return response()->json(['error' => 'Failed to save user'], 500);
        }

        // 処理が成功した場合のレスポンス
        return response()->json(['message' => 'ユーザー登録が完了しました！'], 201);
    }

    // ログイン処理
    public function login(Request $request)
    {
        try{
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
                '$request_password' => $request ->password,
            ]);

            // メールアドレスとパスワードが一致する場合
            if ($request->email == $user->email && $request->password  == $user->password)
            {

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

        }
        catch (\Exception $e)
        {
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

        Log::info('authenticatedUser: '. $authenticatedUser);

        // データベースのユーザー情報を再取得
        $user = User::with('address.prefectures') // addressとprefecture情報も一緒に取得
        ->where('user_id', $authenticatedUser->user_id)
        ->first();

        Log::info('user'. $user);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // 照合後のユーザー情報を返す
        return response()->json($user);
    }
}

