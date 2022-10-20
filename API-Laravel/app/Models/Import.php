<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'importer_name',
        'provider_id',
        'provider_name',
        'provider_tax_id',
        'import_date'
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    } 

    public function importDetails()
    {
        return $this->hasMany(ImportDetail::class);
    }
}
