<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalRequests extends Model
{
    use HasFactory;

    public function host(){
        return $this->belongsTo(Host::class);
    }

    // scope

    public function scopePending()
    {
        return $this->where('status', 1);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 2);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 3);
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', 4);
    }
}
