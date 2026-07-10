<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'public_id','client_request_id','branch_id','branch_unit_id','register_id','employee_id',
        'order_number','order_date','type','status','customer_id','subtotal_cents','discount_cents','tax_cents','total_cents'
    ];
}
