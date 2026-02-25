<?php

namespace App\Models;

use App\Traits\BelongsToProperty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use BelongsToProperty, HasFactory;

    protected $fillable = [
        'name',
        'property_id',
        'perm_assets',
        'perm_users',
        'perm_categories',
        'perm_departments',
        'perm_roles',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
