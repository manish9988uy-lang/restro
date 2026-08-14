<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'email', 'phone', 'address', 'total_spent', 'visit_count', 'notes', 'is_active', 'loyalty_points', 'membership_tier', 'birthday', 'referral_code', 'referred_by'];

    protected $casts = ['total_spent' => 'float'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($customer) {
            if (empty($customer->referral_code)) {
                $customer->referral_code = strtoupper(Str::random(8));
            }
        });
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function referredBy()
    {
        return $this->belongsTo(Customer::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(Customer::class, 'referred_by');
    }
}
