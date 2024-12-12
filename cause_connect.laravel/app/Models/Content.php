<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $table = 'content'; //テーブル名を指定

    protected $fillable = ['case_id', 'picture_type', 'image_path'];

    public $timestamps = false; //タイムスタンプを無効化

    public function request()
    {
        return $this->belongsTo(Request::class, 'case_id');
    }
}
