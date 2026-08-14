<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_item_id',
        'yield_quantity',
        'yield_unit',
        'calculated_cost',
        'instructions',
        'is_active',
    ];

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function ingredients()
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    public function recalculateCost(): float
    {
        $totalCost = 0;
        foreach ($this->ingredients as $ri) {
            $cost = ($ri->ingredient ? $ri->ingredient->cost_per_unit : 0) * $ri->quantity;
            $ri->update(['cost' => $cost]);
            $totalCost += $cost;
        }
        $this->update(['calculated_cost' => $totalCost]);
        return $totalCost;
    }
}
