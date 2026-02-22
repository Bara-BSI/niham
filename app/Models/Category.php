<?php

namespace App\Models;

use App\Traits\BelongsToProperty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use BelongsToProperty, HasFactory;

    protected $fillable = ['name', 'code', 'notes', 'property_id'];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}
