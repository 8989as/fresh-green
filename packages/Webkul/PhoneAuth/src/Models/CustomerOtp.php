<?php

namespace Webkul\PhoneAuth\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Webkul\PhoneAuth\Contracts\CustomerOtp as CustomerOtpContract;

class CustomerOtp extends Model implements CustomerOtpContract
{
    protected $fillable = [
        'customer_id', 'phone', 'otp', 'expires_at', 'used'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
    ];

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function customer()
    {
        return $this->belongsTo(\Webkul\PhoneAuth\Models\Customer::class, 'customer_id');
    }
}
