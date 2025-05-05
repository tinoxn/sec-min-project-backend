<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_code',
        'customer_name',
        'status',
        'order_date'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}