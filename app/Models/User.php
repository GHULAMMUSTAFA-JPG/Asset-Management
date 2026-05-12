<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'company_id',
        'name',
        'email',
        'password',
        'role',
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
    ];

    // this user belongs to one company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // helper: check if user is super admin
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    // helper: check if user is company admin
    public function isCompanyAdmin(): bool
    {
        return $this->role === 'company_admin';
    }
}