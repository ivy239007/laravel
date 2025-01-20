<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseModel extends Model
{
    protected $table = 'cases'; // 依頼情報テーブル（仮）

    protected $primaryKey = 'case_id';

    // ✅ 依頼者（仮に 'requester_id' がある場合）
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id', 'user_id');
    }

    // ✅ 出資者（supテーブルとのリレーション）
    public function contributors()
    {
        return $this->belongsToMany(User::class, 'sup', 'case_id', 'user_id')
                    ->withPivot('sup_point')
                    ->withTimestamps();
    }

    // ✅ 実行者（actテーブルとのリレーション）
    public function executors()
    {
        return $this->belongsToMany(User::class, 'act', 'case_id', 'user_id')
                    ->withPivot('leader')
                    ->withTimestamps();
    }
}
