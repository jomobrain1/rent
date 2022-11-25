<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seater extends Model
{
    use HasFactory;

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'seater_id')->where('status', 1);
    }
}
