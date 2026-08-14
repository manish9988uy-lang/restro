<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'unit',
        'current_stock',
        'alert_threshold',
        'cost_per_unit',
        'location',
        'is_active',
        'notes',
    ];

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function wasteLogs()
    {
        return $this->hasMany(WasteLog::class);
    }

    public function recipeIngredients()
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->alert_threshold;
    }
}
