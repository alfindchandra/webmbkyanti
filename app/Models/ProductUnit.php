<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'conversion' => 'decimal:3',
        'price' => 'decimal:2',
        'min_qty' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function prices()
    {
        return $this->hasMany(ProductUnitPrice::class)->orderBy('min_quantity');
    }

    /**
     * Get the price for a given quantity
     */
    public function getPriceForQuantity($quantity)
    {
        $price = $this->prices()
            ->where('min_quantity', '<=', $quantity)
            ->orderByDesc('min_quantity')
            ->first();

        return $price ? $price->price : $this->price;
    }
}
