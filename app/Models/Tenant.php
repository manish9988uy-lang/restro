<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'domain',
        'email',
        'phone',
        'plan_name',
        'trial_ends_at',
        'subscription_ends_at',
        'is_active',
        'database_name',
        'max_orders',
        'max_branches',
        'max_users',
        'feature_flags',
    ];

    protected $casts = [
        'trial_ends_at' => 'date',
        'subscription_ends_at' => 'date',
        'is_active' => 'boolean',
        'feature_flags' => 'array',
    ];

    public function hasFeature(string $feature): bool
    {
        return $this->feature_flags[$feature] ?? false;
    }
}
