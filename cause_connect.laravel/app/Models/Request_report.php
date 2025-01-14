<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request_report extends Model
{
    use HasFactory;

    // テーブル名を明示的に指定
    protected $table = 'request_report';

    // 主キーを指定
    protected $primaryKey = 'case_id';

    // 主キーが自動増分でない場合
    public $incrementing = false;

    // 主キーのデータ型
    protected $keyType = 'integer';

    // 必要なフィールドを指定
    protected $fillable = [
        'case_id',
        'comment1',
        'comment2',
        'comment3',
        'comment4',
    ];
}
