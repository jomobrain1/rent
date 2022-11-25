<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostEmailLogs extends Model
{
    use HasFactory;

    public function host(){
    	return $this->belongsTo(Host::class);
    }
}
