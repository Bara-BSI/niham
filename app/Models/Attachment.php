<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'path',
        'type',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
