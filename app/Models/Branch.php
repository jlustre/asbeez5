<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_category_id', 'code', 'name', 'description', 'phone', 'email',
        'address_line1', 'address_line2', 'city', 'state', 'postal_code', 'country',
        'latitude', 'longitude', 'manager_employee_id', 'assistant_manager_employee_id',
        'pricing_type', 'opening_hours', 'is_active', 'extra',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'is_active' => 'boolean',
        'opening_hours' => 'array',
        'extra' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(BusinessCategory::class, 'business_category_id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(BranchUnit::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_employee_id');
    }

    public function assistantManager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assistant_manager_employee_id');
    }
}
