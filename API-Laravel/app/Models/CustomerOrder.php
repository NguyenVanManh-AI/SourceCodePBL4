<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'hex_id',
        'customer_id',
        'customer_name',
        'recipient_name',
        'phone_number',
        'address',
        'order_status',
        'order_time',
        'confirm_time',
        'ship_time',
        'completed_time',
        'shipping_fee',          
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    } 
}
