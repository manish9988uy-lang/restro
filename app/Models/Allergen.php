<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Allergen extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'icon', 'description'];
    
    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class);
    }
}
