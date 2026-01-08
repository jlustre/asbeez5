<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'email',
        'phone',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country_id',
        'timezone_id',
        'logo_url',
        'banner_url',
        'commission_rate',
        'is_active',
        'verification_status',
        'rating_avg',
        'rating_count',
        'meta',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'commission_rate' => 'decimal:2',
        'rating_avg' => 'decimal:2',
        'meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function timezone()
    {
        return $this->belongsTo(Timezone::class);
    }

    public function commissionAssignments()
    {
        return $this->hasMany(VendorCommissionRate::class);
    }
}
