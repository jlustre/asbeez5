<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id','line_number','product_id','sku','name','qty','unit_price_cents','discount_cents','tax_rate_bp','tax_cents','total_cents','route_station','status','notes'
    ];
}
