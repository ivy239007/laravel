<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sup extends Model
{
    protected $table = 'sup';
    public $timestamps = false;

    // ✅ 登録できるカラム
    protected $fillable = [
        'user_id',
        'case_id',
        'sup_point',
    ];

    public $incrementing = false;
}
