<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ComboMeal extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 'description', 'price', 'savings', 'image', 'is_available', 'sort_order'
    ];
    
    protected $casts = [
        'price' => 'float',
        'savings' => 'float',
        'is_available' => 'boolean',
    ];
    
    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class)->withPivot(['is_required', 'quantity']);
    }
}
