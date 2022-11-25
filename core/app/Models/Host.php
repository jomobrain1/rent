<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Host extends Authenticatable
{
    use HasFactory;
    use Notifiable, HasApiTokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guard = 'host';

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'address' => 'object',
        'ver_code_send_at' => 'datetime'
    ];

    protected $data = [
        'data'=>1
    ];

    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status','!=',0)->with(['host','user', 'gateway']);
    }

    public function requests()
    {
        return $this->hasMany(WithdrawalRequests::class)->where('status','!=',0);
    }

    public function wallet(){
        return $this->hasOne(Wallet::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
    public function rents()
    {
        return $this->hasMany(RentLog::class);
    }

    public function login_logs()
    {
        return $this->hasMany(HostLogin::class);
    }

    // SCOPES
    public function scopeActive()
    {
        return $this->where('status', 1);
    }

    public function scopeBanned()
    {
        return $this->where('status', 0);
    }

    public function scopeEmailUnverified()
    {
        return $this->where('ev', 0);
    }

    public function scopeSmsUnverified()
    {
        return $this->where('sv', 0);
    }
    public function scopeEmailVerified()
    {
        return $this->where('ev', 1);
    }

    public function scopeSmsVerified()
    {
        return $this->where('sv', 1);
    }

}

