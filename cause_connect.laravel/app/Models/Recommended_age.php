<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommended_age extends Model
{
    use HasFactory;

    protected $table = 'recommended_age'; // テーブル名を明示的に指定
}
