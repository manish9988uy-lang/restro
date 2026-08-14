<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'icon', 'color', 'is_active', 'sort_order'];

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }
}
