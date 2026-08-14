<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuItemVariant extends Model
{
    use HasFactory;
    
    protected $fillable = ['menu_item_id', 'name', 'options', 'is_required', 'sort_order'];
    
    protected $casts = [
        'options' => 'json',
        'is_required' => 'boolean',
    ];
    
    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
