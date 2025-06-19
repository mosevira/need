<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'birth_date',
        'phone',
        'start_job_date',
        'end_job_date',
        'email',
        'role',
        'branch_id',
        'is_active',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // Методы проверки ролей
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStorekeeper(): bool
    {
        return $this->role === 'storekeeper';
    }

    public function isSeller(): bool
    {
        return $this->role === 'seller';
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    // Совместимость с вашим существующим методом hasRole
    public function hasRole($role): bool
    {
        return $this->role === $role;
    }

    // Отношения
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    // Scope-методы для удобства выборки
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeStorekeepers($query)
    {
        return $query->where('role', 'storekeeper');
    }

    public function scopeSellers($query)
    {
        return $query->where('role', 'seller');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}