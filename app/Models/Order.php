<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_no', 'table_id', 'customer_id', 'coupon_id', 'user_id',
        'order_type', 'order_status', 'payment_status', 'payment_type',
        'sub_total', 'vat', 'discount', 'tip', 'total', 'pay_amount', 'due_amount',
        'refund_amount', 'refund_reason', 'notes', 'scheduled_at', 'preparing_at', 'branch_id'
    ];

    protected $casts = [
        'sub_total'  => 'float',
        'vat'        => 'float',
        'discount'   => 'float',
        'tip'        => 'float',
        'total'      => 'float',
        'pay_amount' => 'float',
        'due_amount' => 'float',
        'refund_amount' => 'float',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function table()
    {
        return $this->belongsTo(RestaurantTable::class, 'table_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class)->latest();
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->order_status) {
            'pending'   => 'warning',
            'preparing' => 'info',
            'ready'     => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
            default     => 'secondary',
        };
    }
}
