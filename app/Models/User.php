<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role_id',
        'department_id',
        'property_id',
        'is_super_admin',
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
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function isRole(string $name): bool
    {
        return optional($this->role)->name === $name;
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function isSuperAdmin(): bool
    {
        return (bool) $this->is_super_admin;
    }

    /**
     * Get the active property id for this user.
     * Super admin: from session or null (all).
     * Normal user: from their property_id.
     */
    public function activePropertyId(): ?int
    {
        if ($this->isSuperAdmin()) {
            return session('active_property_id');
        }

        return $this->property_id;
    }

    /**
     * Check if user has explicit string permission on a module.
     */
    public function hasPermission(string $module, string $action): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $perm = $this->role->{$module} ?? 'no access';

        if ($perm === 'full access') {
            return true;
        }

        if ($action === 'view') {
            return $perm !== 'no access';
        }

        return str_contains($perm, $action);
    }

    /**
     * Determine if user has executive oversight based on their department.
     */
    public function hasExecutiveOversight(): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        return optional($this->department)->is_executive_oversight == true;
    }
}
