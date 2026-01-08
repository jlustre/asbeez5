<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country_id',
        'timezone_id',
        'background_url',
        'birthdate',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function timezone()
    {
        return $this->belongsTo(Timezone::class);
    }

    public function fullName()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
