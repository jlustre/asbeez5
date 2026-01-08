<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'iso2',
        'iso3',
        'phone_code',
        'currency_code',
        'currency_name',
        'region',
        'subregion',
    ];

    public function timezones()
    {
        return $this->belongsToMany(Timezone::class, 'country_timezone')->withTimestamps();
    }
}
