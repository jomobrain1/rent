<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanLog extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function pick_up_location()
    {
        return $this->belongsTo(Location::class, 'pick_location');
    }

    //Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('pick_time', '>', now());
    }

    public function scopeRunning($query)
    {
        return $query->where('pick_time', '<', now())->where('drop_time', '>', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('drop_time', '<', now());
    }
}
