<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function prefecture()
    {
        return $this->belongsTo(Prefecture::class, 'pref_id', 'pref_id');
    }
}
