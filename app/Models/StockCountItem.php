<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockCountItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_count_id',
        'ingredient_id',
        'system_stock',
        'counted_stock',
        'variance',
        'unit_cost',
        'variance_cost',
    ];

    public function stockCount()
    {
        return $this->belongsTo(StockCount::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
