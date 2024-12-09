<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Prefectures;

class Address extends Model
{
    use HasFactory;

    public $timestamps = false; // タイムスタンプ管理を無効化

    protected $table = 'address';
    protected $primaryKey = 'address_id';

    protected $fillable = [
        'pref_id',
        'address1',
        'address2',
        'post_code',
    ];

    public function prefectures()
    {
        return $this->belongsTo(Prefectures::class,'pref_id');
    }
}
