<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_reference',
        'customer_name',
        'customer_email',
        'customer_phone',
        'total_amount',
        'payment_status',
        'payment_reference',
        'payment_data'
    ];
    
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}