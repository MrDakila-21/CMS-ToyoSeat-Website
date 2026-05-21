<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'display_name',
        'account_type',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if user is a super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->account_type === 'superadmin';
    }

    /**
     * Check if user is an admin (including superadmin)
     */
    public function isAdmin(): bool
    {
        return in_array($this->account_type, ['admin', 'superadmin']);
    }

    /**
     * Check if user account is active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Get display name (fallback to name if display_name is empty)
     */
    public function getDisplayNameAttribute($value)
    {
        return $value ?: $this->name;
    }

    /**
     * Scope a query to only include active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include super admins
     */
    public function scopeSuperAdmins($query)
    {
        return $query->where('account_type', 'superadmin');
    }

    /**
     * Scope a query to only include regular admins (not superadmin)
     */
    public function scopeRegularAdmins($query)
    {
        return $query->where('account_type', 'admin');
    }
}