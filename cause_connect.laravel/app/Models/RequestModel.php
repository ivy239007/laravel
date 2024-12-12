<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'case_name', 'achieve', 'lower_limit', 'upper_limit',
        'case_date', 'start_activty', 'end_activty', 'address_id', 'equipment',
        'area_id', 'theme_id', 'rec_age_id', 'feature_id', 'area_detail',
        'content', 'contents', 'state_id',
    ];
}
