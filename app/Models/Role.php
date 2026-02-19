<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'can_create',
        'can_read',
        'can_update',
        'can_delete'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
