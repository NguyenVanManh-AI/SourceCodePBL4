<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'customer_order_id',
        'product_id',
        'product_name',
        'quantity',
        'price',
    ];

    public function customerOrder()
    {
        return $this->belongsTo(CustomerOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
