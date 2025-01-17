<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Act extends Model
{
    protected $table = 'act';

    protected $fillable = [
        'user_id',
        'case_id',
        'leader',
    ];

    public $timestamps = false;  // タイムスタンプがない場合
}
