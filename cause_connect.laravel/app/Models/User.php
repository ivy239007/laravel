<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $table = 'user'; // 使用するテーブル名
    protected $primaryKey = 'user_id'; // 主キー
    public $incrementing = true;
    protected $keyType = 'int';


    protected $fillable = [
        'password',
        'nickname',
        'name',
        'kana',
        'birth',
        'sex',
        'tel',
        'email',
        'address_id',
        'intro',
        'icon',
    ];

    protected $hidden = [
        'password', // パスワードを隠す
    ];

    public function getIconAttribute($value)
    {
        return $value; // 値をそのまま返す
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'address_id');
    }
}
