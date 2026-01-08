<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'tier',
        'description',
        'rate',
        'min_orders',
        'max_orders',
        'min_order_value',
        'max_order_value',
        'min_revenue',
        'qualifications',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'min_order_value' => 'decimal:2',
        'max_order_value' => 'decimal:2',
        'min_revenue' => 'decimal:2',
        'qualifications' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function vendorCommissionRates()
    {
        return $this->hasMany(VendorCommissionRate::class);
    }
}
