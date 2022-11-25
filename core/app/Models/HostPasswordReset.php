<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostPasswordReset extends Model
{
    use HasFactory;

    protected $table = "host_password_resets";
    protected $guarded = ['id'];
    public $timestamps = false;
}
