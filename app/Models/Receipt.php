<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id','register_id','receipt_number','receipt_date','is_reprint','reprint_of','payload','printed_at'
    ];
}
