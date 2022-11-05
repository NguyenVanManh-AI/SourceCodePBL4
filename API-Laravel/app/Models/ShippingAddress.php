<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'customer_id',
        'recipient_name',
        'phone_number',
        'address'            
    ];

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }
}
