<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestaurantTable extends Model
{
    use HasFactory;

    protected $table = 'restaurant_tables';

    protected $fillable = ['table_number', 'name', 'capacity', 'location', 'status', 'position_x', 'position_y', 'width', 'height', 'shape'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'table_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'available'   => 'success',
            'occupied'    => 'danger',
            'reserved'    => 'warning',
            'maintenance' => 'secondary',
            default       => 'secondary',
        };
    }
}
