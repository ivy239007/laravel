<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Models\Prefectures;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    use HasFactory;

    protected $table = 'user'; // 使用するテーブル名を明示的に指定

    protected $primaryKey = 'user_id';

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

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'address_id');
    }
}
