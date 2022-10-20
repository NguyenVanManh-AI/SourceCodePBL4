<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'address',
        'phone',
        'email',
        'tax_id_number'
    ];
    public function imports()
    {
        return $this->hasMany(Import::class);
    }
}
