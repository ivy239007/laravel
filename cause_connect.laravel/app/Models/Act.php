<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Act extends Model
{
    use HasFactory;

    protected $table = 'act'; // 使用するテーブル名

    public $timestamps = false;


    protected $fillable = [
        'user_id',
        'case_id',
        'leader',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    public function request()
    {
        return $this->belongsTo(Request::class,'case_id','case_id');
    }
}
