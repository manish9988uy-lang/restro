<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'type', 'value', 'min_order',
        'valid_from', 'valid_until', 'usage_limit',
        'used_count', 'is_active'
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isValid($subtotal): bool
    {
        if (!$this->is_active) return false;
        if ($this->valid_from && $this->valid_from->isFuture()) return false;
        if ($this->valid_until && $this->valid_until->isPast()) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        if ($this->min_order && $subtotal < $this->min_order) return false;
        return true;
    }

    public function calculateDiscount($subtotal): float
    {
        if ($this->type === 'percentage') {
            return round($subtotal * ($this->value / 100), 2);
        }
        return $this->value;
    }
}
