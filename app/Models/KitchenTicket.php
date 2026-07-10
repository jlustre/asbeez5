<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitchenTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id','ticket_number','station','status','routed_at','started_at','ready_at','served_at'
    ];
}
