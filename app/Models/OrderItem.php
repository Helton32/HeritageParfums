<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_type',
        'product_size',
        'product_price',
        'quantity',
        'total_price',
    ];

    protected $casts = [
        'product_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors
    public function getFormattedProductPriceAttribute()
    {
        return number_format($this->product_price, 2) . ' €';
    }

    public function getFormattedTotalPriceAttribute()
    {
        return number_format($this->total_price, 2) . ' €';
    }

    // Methods
    public function calculateTotalPrice()
    {
        $this->total_price = $this->quantity * $this->product_price;
        $this->save();
    }
}