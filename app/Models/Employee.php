<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'branch_id',
        'employee_code',
        'name',
        'email',
        'phone',
        'role',
        'pos_pin',
        'is_active',
        'hired_at',
        'terminated_at',
        'permission_level',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'hired_at' => 'datetime',
        'terminated_at' => 'datetime',
        'permission_level' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
