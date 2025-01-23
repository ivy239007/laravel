<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    protected $table = 'favorite'; // データベースのテーブル名
    protected $primaryKey = null; // 複合主キーは null にしておく
    public $incrementing = false; // 主キーがインクリメントしない場合は false に
    public $timestamps = false;  // タイムスタンプがない場合
    protected $fillable = ['case_id','user_id','favo_flg']; // 保存可能なカラム

    public static function updateOrCreateCustom($attributes, $values)
    {
        $existingFavorite = self::where('case_id', $attributes['case_id'])
            ->where('user_id', $attributes['user_id'])
            ->first();

        if ($existingFavorite) {
            // 既存のレコードがあれば更新
            $existingFavorite->update($values);
            return $existingFavorite;
        }

        // なければ新規作成
        return self::create(array_merge($attributes, $values));
    }

    public function case()
    {
        return $this->belongsTo(RequestModel::class, 'case_id', 'case_id');
    }
}
