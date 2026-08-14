<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'menu_item_id', 'quantity', 'unit_price', 'subtotal', 'notes', 'variants', 'addons'];

    protected $casts = [
        'unit_price' => 'float',
        'subtotal'   => 'float',
        'variants'   => 'json',
        'addons'     => 'json',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
