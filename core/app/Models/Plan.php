<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $casts = [
        'included' => 'object',
        'excluded' => 'object'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
