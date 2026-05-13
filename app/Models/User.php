<?php

namespace App\Models;

use App\Helpers\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'password',
        'role',
        'permissions',
        'is_active',
        'is_verified',
        'email_verified_at',
        'token_invalidated_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'token_invalidated_at',
    ];

    protected $casts = [
        'email_verified_at'    => 'datetime',
        'token_invalidated_at' => 'datetime',
        'is_active'            => 'boolean',
        'is_verified'          => 'boolean',
        'password'             => 'hashed',
        'permissions'          => 'array',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isCompanyAdmin(): bool
    {
        return $this->role === 'company_admin';
    }

    public function hasPermission(string $permission): bool
    {
        $rolePermissions = Role::permissionsFor($this->role ?? '');
        $userPermissions = $this->permissions ?? [];
        $allPermissions  = array_merge($rolePermissions, $userPermissions);

        return in_array($permission, $allPermissions, true);
    }
}