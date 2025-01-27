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

    public $timestamps = false; // タイムスタンプを無効化

    // プライマリキーを無効化
    protected $primaryKey = null;
    public $incrementing = false;

    // 自動的に created を設定する（任意）
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created = $model->created ?? now(); // 現在時刻を設定
        });
    }
}
