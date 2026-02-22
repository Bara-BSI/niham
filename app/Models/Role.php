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
        'can_create',
        'can_read',
        'can_update',
        'can_delete',
        'property_id',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
