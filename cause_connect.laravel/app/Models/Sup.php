<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sup extends Model
{
    use HasFactory;

    protected $table = 'sup'; // 使用するテーブル名を明示的に指定

    public $timestamps = false; // タイムスタンプ管理を無効化

    protected $fillable = [
        'user_id',
        'case_id',
        'sup_point',

    ];

}
