<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $table = 'deposits';
    protected $guarded = ['id'];

    protected $casts = [
        'detail' => 'object'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function host(){
        return $this->belongsTo(Host::class);
    }
    public function gateway()
    {
        return $this->belongsTo(Gateway::class, 'method_code', 'code');
    }
    public function rent()
    {
        return $this->belongsTo(RentLog::class, 'rent_id', 'id')->with('vehicle');
    }
    public function planlog()
    {
        return $this->belongsTo(PlanLog::class, 'plan_id', 'id')->with('plan');
    }

    // scope
    public function scopegatewayCurrency()
    {
        return GatewayCurrency::where('method_code', $this->method_code)->where('currency', $this->method_currency)->first();
    }

    public function scopeBaseCurrency()
    {
        return $this->gateway->crypto == 1 ? 'USD' : $this->method_currency;
    }

    public function scopePending()
    {
        return $this->where('status', 2);
    }
}
