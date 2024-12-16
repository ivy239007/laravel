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
    // JSONレスポンスに含めたくない属性
    protected $hidden = [
        'password',
    ];

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'address_id');
    }
    /**
     * バイナリデータをBase64エンコードして返す
     */
    public function getIconAttribute($value)
    {
        // データが存在しない場合はnullを返す
        if (!$value) {
            return null;
        }

        // Base64エンコードしてデータURL形式で返す
        return 'data:image/jpeg;base64,' . base64_encode($value);
    }
}
