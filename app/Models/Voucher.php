<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'start_date',
        'end_date',
        'usage_limit',
        'used_count',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isValidForAmount($amount)
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        if ($this->start_date && $this->start_date > Carbon::now()) {
            return false;
        }

        if ($this->end_date && $this->end_date < Carbon::now()) {
            return false;
        }

        if ($this->min_order_amount > 0 && $amount < $this->min_order_amount) {
            return false;
        }

        return true;
    }

    public function calculateDiscount($amount)
    {
        if (!$this->isValidForAmount($amount)) {
            return 0;
        }

        if ($this->discount_type === 'percent') {
            $discount = $amount * ($this->discount_value / 100);
            return $discount;
        }

        return $this->discount_value;
    }
}
