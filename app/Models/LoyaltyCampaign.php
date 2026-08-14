<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyCampaign extends Model
{
    protected $fillable = [
        'name', 'points_per_amount', 'amount_for_points',
        'reward_type', 'reward_value', 'valid_from', 'valid_until', 'is_active'
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    public function isActive()
    {
        if (!$this->is_active) return false;
        if ($this->valid_from && $this->valid_from->isFuture()) return false;
        if ($this->valid_until && $this->valid_until->isPast()) return false;
        return true;
    }
}
