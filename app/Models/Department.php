<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name','code','notes'];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
