<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryZone extends Model
{
    protected $fillable = ['name', 'base_fee', 'min_order', 'description', 'is_active'];

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class);
    }
}
