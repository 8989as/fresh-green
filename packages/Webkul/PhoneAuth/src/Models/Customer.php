<?php

namespace Webkul\PhoneAuth\Models;

use Webkul\Customer\Models\Customer as BaseCustomer;

class Customer extends BaseCustomer
{
    protected $fillable = [
        'first_name', 'last_name', 'phone', 'phone_verified', 'phone_verified_at',
    ];

    protected $casts = [
        'phone_verified' => 'boolean',
        'phone_verified_at' => 'datetime',
    ];

    public function otps()
    {
        return $this->hasMany(CustomerOtp::class, 'customer_id');
    }

    public function hasVerifiedPhone()
    {
        return $this->phone_verified;
    }
}
