<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    protected $table = 'picture'; // テーブル名を指定
    public $timestamps = false; // タイムスタンプ不要
    protected $primaryKey = 'picture_type'; // 主キー
    protected $fillable = ['picture_type', 'type_name']; // 変更可能なカラム
}
