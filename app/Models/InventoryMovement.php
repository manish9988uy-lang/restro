<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'ingredient_id',
        'type',
        'quantity',
        'cost_per_unit',
        'total_cost',
        'reference_type',
        'reference_id',
        'batch_number',
        'expiry_date',
        'user_id',
        'notes',
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
