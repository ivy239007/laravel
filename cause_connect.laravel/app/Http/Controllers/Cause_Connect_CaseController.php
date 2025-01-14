<?php

namespace App\Http\Controllers;

use App\Models\Request as RequestModel; // Requestモデルをインポート
use App\Models\Sup;
use App\Models\Act;
use App\Models\Address;
use App\Models\Content; // 新たにContentモデルをインポート
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  // DBクラスをインポート
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Cause_Connect_CaseController extends Controller
{
    public function stores(Request $request)
    {
        Log::info('Received request: ' . json_encode($request->all()));

        // トランザクションを開始
        DB::beginTransaction();

        try {
            // 住所情報を登録
            $address = Address::create([
                'pref_id' => $request->pref_id,
                'address1' => $request->address1,
                'address2' => $request->address2,
            ]);

            $addressId = $address->address_id;
            Log::info('Generated Address ID: ' . $addressId);

            // 依頼情報を登録
            $case = RequestModel::create([
                'client_id' => $request->client_id,
                'case_name' => $request->case_name,
                'lower_limit' => $request->lower_limit,
                'upper_limit' => $request->upper_limit,
                'exec_date' => $request->exec_date,
                'start_activty' => $request->start_activty,
                'end_activty' => $request->end_activty,
                'address_id' => $addressId,
                'equipment' => $request->equipment,
                'area_id' => $request->area_id,
                'theme_id' => $request->theme_id,
                'rec_age_id' => $request->rec_age_id,
                'feature_id' => $request->feature_id,
                'achieve' => $request->achieve,
                'area_detail' => $request->area_detail,
                'content' => $request->content,
                'contents' => $request->contents,
                'google_map' => $request->google_map,
                'case_date' => now(),
                'state_id' => $request->state_id,
                'num_people' => $request->participation_id,
            ]);

            $caseId = $case->id;
            Log::info('Generated Case ID: ' . $caseId);

            // 写真を保存
            $uploadedPhotos = [];

            if ($request->hasFile('photo1')) {
                $path = $request->file('photo1')->store('uploads/photos', 'public');
                $uploadedPhotos[] = [
                    'case_id' => $caseId,
                    'picture_type' => 1, // photo1 の場合は picture_type = 1
                    'picture' => $path,
                ];
                Log::info('Saved photo1 at: ' . $path);
            }

            if ($request->hasFile('photo2')) {
                $path = $request->file('photo2')->store('uploads/photos', 'public');
                $uploadedPhotos[] = [
                    'case_id' => $caseId,
                    'picture_type' => 2, // photo2 の場合は picture_type = 2
                    'picture' => $path,
                ];
                Log::info('Saved photo2 at: ' . $path);
            }

            // 写真情報をcontentテーブルに保存
            foreach ($uploadedPhotos as $photo) {
                DB::table('content')->insert($photo);
            }

            // Sup情報を登録
            Sup::create([
                'user_id' => $request->client_id,
                'case_id' => $caseId,
                'sup_point' => $request->sup_point,
            ]);

            if ($request->participation_id == 1) {
                Act::create([
                    'user_id' => $request->client_id,
                    'case_id' => $caseId,
                    'leader' => 1,
                ]);
            }

            // トランザクションをコミット
            DB::commit();

            return response()->json(['message' => '依頼が正常に投稿されました'], 201);
        } catch (\Exception $e) {
            // トランザクションをロールバック
            DB::rollBack();
            Log::error('Error during request processing: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to save request'], 500);
        }
    }
}
