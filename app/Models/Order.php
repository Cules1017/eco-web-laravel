<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'payment_status',
        'payment_method',
        'payment_transaction_id',
        'payment_request_id',
        'payment_payload',
        'paid_at',
        'shipping_address_id',
        'total_amount',
        'notes',
    ];

    protected $casts = [
        'total_amount'    => 'decimal:2',
        'payment_payload' => 'array',
        'paid_at'         => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function getTotalAttribute()
    {
        // Nếu total_amount đã có, trả về nó, nếu không thì tính tổng các item
        if (!is_null($this->total_amount)) {
            return $this->total_amount;
        }
        return $this->items->sum('subtotal');
    }
}
