<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HeldOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'hold_name', 'table_id', 'customer_id', 'user_id',
        'order_type', 'cart', 'notes'
    ];

    protected $casts = [
        'cart' => 'array',
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
}
