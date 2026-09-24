<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, TwoFactorAuthenticatable;

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->username)) {
                $base = !empty($user->email) ? explode('@', $user->email)[0] : Str::slug($user->name ?: 'user');
                $user->username = Str::slug($base) . '_' . Str::lower(Str::random(4));
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'photo',
        'email_verified_at',
        'job_description',
        'location',
        'branch_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
    ];



    public function getRouteKeyName()
    {
        return 'username';
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        });
    }

    public static function getPermissionGroups()
    {
        $permission_groups = Permission::select('group_name')->groupBy('group_name')->get();

        return $permission_groups;
    }

    public static function getPermissionByGroupName(String $group_name)
    {
        $permissions = Permission::select('id', 'name')->where('group_name', $group_name)->get();

        return $permissions;
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public static function roleHasPermission($role, $permissions)
    {
        $hasPermission = true;

        foreach ($permissions as $permission) {
            if(!$role->hasPermissionTo($permission->name)) {
                $hasPermission = false;
                return $hasPermission;
            }
            return $hasPermission;
        }
    }
}
