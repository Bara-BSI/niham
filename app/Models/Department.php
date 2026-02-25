<?php

namespace App\Models;

use App\Traits\BelongsToProperty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use BelongsToProperty, HasFactory;

    protected $fillable = ['name', 'code', 'notes', 'property_id', 'is_executive_oversight'];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
