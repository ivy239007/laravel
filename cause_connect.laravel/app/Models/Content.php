<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $table = 'content'; // テーブル名を指定

    protected $fillable = ['case_id', 'picture_type', 'picture']; // 保存可能なカラムを指定

    public $timestamps = false; // タイムスタンプを無効化
}
