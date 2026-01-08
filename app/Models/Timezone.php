<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timezone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'abbreviation',
        'utc_offset',
        'offset_minutes',
    ];

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'country_timezone')->withTimestamps();
    }
}
