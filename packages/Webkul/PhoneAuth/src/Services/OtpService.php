<?php

namespace Webkul\PhoneAuth\Services;

use Webkul\PhoneAuth\Models\CustomerOtp;
use Webkul\PhoneAuth\Services\SmsService;
use Webkul\PhoneAuth\Repositories\CustomerOtpRepository;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OtpService
{
    protected $smsService;
    protected $otpRepo;

    public function __construct(SmsService $smsService, CustomerOtpRepository $otpRepo)
    {
        $this->smsService = $smsService;
        $this->otpRepo = $otpRepo;
    }

    public function generateOtp($phone)
    {
        $otp = str_pad(random_int(0, pow(10, config('phoneauth.otp_length')) - 1), config('phoneauth.otp_length'), '0', STR_PAD_LEFT);
        $expiresAt = Carbon::now()->addMinutes(config('phoneauth.otp_expiry'));
        $otpModel = $this->otpRepo->create([
            'phone' => $phone,
            'otp' => $otp,
            'expires_at' => $expiresAt,
        ]);
        
        try {
            $this->smsService->send($phone, "Your OTP is: $otp");
        } catch (\Exception $e) {
            // Log the error but don't fail the operation
            \Log::error('Failed to send OTP: ' . $e->getMessage());
        }
        
        // For development, log the OTP so you can test without SMS
        if (app()->environment(['local', 'development', 'testing'])) {
            \Log::info("Development OTP for $phone: $otp");
        }
        
        return $otpModel;
    }

    public function verifyOtp($phone, $otp)
    {
        $otpModel = $this->otpRepo->findActiveOtp($phone, $otp);
        if ($otpModel && !$otpModel->isExpired()) {
            $this->otpRepo->markAsUsed($otpModel);
            return true;
        }
        return false;
    }
}
