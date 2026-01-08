<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorCommissionRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'commission_rate_id',
        'starts_at',
        'ends_at',
        'is_active',
        'priority',
        'notes',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function commissionRate()
    {
        return $this->belongsTo(CommissionRate::class);
    }
}
