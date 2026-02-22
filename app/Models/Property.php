<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'address'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }
}
