<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;

    protected $table = 'point'; // テーブル名を明示的に指定

    protected $fillable = [
        'user_id',
        'timestamp',
        'points',
        'description',
    ];

    public $timestamps = false; // 手動で管理する場合
}
