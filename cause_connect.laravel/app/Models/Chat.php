<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chat';

    protected $fillable = [
        'created',
        'case_id',
        'user_id',
        'message',
    ];

    public $timestamps = false; // `created` を使うためタイムスタンプを無効化
}
