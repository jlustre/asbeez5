<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'public_id','client_request_id','order_id','method','amount_cents','change_cents','provider','provider_txn_id','status','captured_at'
    ];
}
