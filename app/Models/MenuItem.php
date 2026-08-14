<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'description', 'price', 'cost_price',
        'image', 'is_available', 'is_featured', 'preparation_time', 'sku', 'stock_quantity',
        'calories', 'protein', 'carbs', 'fat', 'fiber', 'sugar', 'sodium'
    ];

    protected $casts = [
        'price'        => 'float',
        'cost_price'   => 'float',
        'is_available' => 'boolean',
        'is_featured'  => 'boolean',
        'calories'     => 'float',
        'protein'      => 'float',
        'carbs'        => 'float',
        'fat'          => 'float',
        'fiber'        => 'float',
        'sugar'        => 'float',
        'sodium'       => 'float',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function variants()
    {
        return $this->hasMany(MenuItemVariant::class)->orderBy('sort_order');
    }
    
    public function addons()
    {
        return $this->belongsToMany(MenuItemAddon::class)->orderBy('sort_order');
    }
    
    public function allergens()
    {
        return $this->belongsToMany(Allergen::class);
    }
}
