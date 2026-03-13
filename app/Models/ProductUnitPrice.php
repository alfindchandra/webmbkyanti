<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductUnitPrice extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function productUnit()
    {
        return $this->belongsTo(ProductUnit::class);
    }
}
