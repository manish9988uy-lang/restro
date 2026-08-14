<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuItemAddon extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'price', 'description', 'is_available', 'sort_order'];
    
    protected $casts = [
        'price' => 'float',
        'is_available' => 'boolean',
    ];
    
    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class);
    }
}
