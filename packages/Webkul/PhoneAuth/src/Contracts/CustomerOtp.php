<?php

namespace Webkul\PhoneAuth\Contracts;

interface CustomerOtp
{
    public function isExpired();
}
