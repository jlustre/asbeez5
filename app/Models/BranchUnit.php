<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id', 'unit_number', 'code', 'description',
    ];

    protected $casts = [];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
