<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'case_name',
        'achieve',
        'lower_limit',
        'upper_limit',
        'case_date',
        'start_activty',
        'end_activty',
        'address_id',
        'equipment',
        'area_id',
        'theme_id',
        'rec_age_id',
        'feature_id',
        'area_detail',
        'content',
        'contents',
        'state_id'
    ];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function area()
    {
        return $this->belongsTo(ActivityArea::class, 'area_id');
    }

    public function theme()
    {
        return $this->belongsTo(ActivityTheme::class, 'theme_id');
    }

    public function recommendedAge()
    {
        return $this->belongsTo(RecommendedAge::class, 'rec_age_id');
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class, 'feature_id');
    }

    public function content()
    {
        return $this->hasMany(Content::class, 'case_id');
    }
}
