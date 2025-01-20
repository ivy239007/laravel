<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestModel extends Model
{
    protected $table = 'case';          // ✅ テーブル名
    protected $primaryKey = 'case_id';  // ✅ 主キーをcase_idに設定
    public $incrementing = true;        // ✅ オートインクリメントを有効化
    protected $keyType = 'int';         // ✅ 主キーのデータ型を整数型に設定

    protected $fillable = [
        'client_id',
        'case_name',
        'lower_limit',
        'upper_limit',
        'exec_date',
        'start_activty',
        'end_activty',
        'address_id',
        'equipment',
        'area_id',
        'theme_id',
        'rec_age_id',
        'feature_id',
        'achieve',
        'area_detail',
        'content',
        'contents',
        'google_map',
        'case_date',
        'state_id',
        'num_people'
    ];
}
