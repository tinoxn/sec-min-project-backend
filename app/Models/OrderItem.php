<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price', 'total'];

    protected static function booted()
    {
        static::creating(function (OrderItem $item) {
            // Auto-fetch price if not set
            if (!$item->price) {
                $item->price = $item->product->price;
            }
            // Auto-calculate total
            $item->total = $item->quantity * $item->price;
        });

        static::updating(function (OrderItem $item) {
            // Recalculate total if quantity changes
            if ($item->isDirty('quantity')) {
                $item->total = $item->quantity * $item->price;
            }
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}