<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'import_id',
        'product_id',
        'product_name',
        'quantity',
        'price',
        'tax'
    ];

    public function import()
    {
        return $this->belongsTo(Import::class);
    } 

    public function product()
    {
        return $this->belongsTo(Product::class);
    } 
}
