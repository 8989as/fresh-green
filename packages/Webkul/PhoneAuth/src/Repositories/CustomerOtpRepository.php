<?php

namespace Webkul\PhoneAuth\Repositories;

use Webkul\PhoneAuth\Models\CustomerOtp;

class CustomerOtpRepository
{
    public function create(array $data)
    {
        return CustomerOtp::create($data);
    }

    public function findActiveOtp($phone, $otp)
    {
        return CustomerOtp::where('phone', $phone)
            ->where('otp', $otp)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();
    }

    public function markAsUsed(CustomerOtp $otp)
    {
        $otp->used = true;
        $otp->save();
    }
}
