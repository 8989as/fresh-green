<?php

namespace Webkul\PhoneAuth\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Webkul\PhoneAuth\Models\Customer;
use Webkul\PhoneAuth\Services\OtpService;
use Webkul\PhoneAuth\Repositories\CustomerOtpRepository;
use Webkul\PhoneAuth\Http\Requests\SendOtpRequest;
use Webkul\PhoneAuth\Http\Requests\VerifyOtpRequest;
use Webkul\PhoneAuth\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    protected $otpService;
    protected $otpRepo;

    public function __construct(OtpService $otpService, CustomerOtpRepository $otpRepo)
    {
        $this->otpService = $otpService;
        $this->otpRepo = $otpRepo;
    }

    public function sendOtp(SendOtpRequest $request)
    {
        $phone = $request->input('phone');
        $this->otpService->generateOtp($phone);
        return response()->json(['message' => 'OTP sent successfully.']);
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        $phone = $request->input('phone');
        $otp = $request->input('otp');
        if ($this->otpService->verifyOtp($phone, $otp)) {
            $customer = Customer::where('phone', $phone)->first();
            if ($customer) {
                $customer->phone_verified = true;
                $customer->phone_verified_at = now();
                $customer->save();
                // Issue token (Sanctum)
                $token = $customer->createToken('api')->plainTextToken;
                return response()->json(['token' => $token, 'customer' => $customer]);
            }
            return response()->json(['message' => 'Phone verified, but customer not found.'], 404);
        }
        return response()->json(['message' => 'Invalid or expired OTP.'], 422);
    }

    public function register(RegisterRequest $request)
    {
        $phone = $request->input('phone');
        $customer = Customer::create([
            'first_name' => $request->input('first_name', ''),
            'last_name' => $request->input('last_name', ''),
            'phone' => $phone,
            'phone_verified' => false,
        ]);
        $this->otpService->generateOtp($phone);
        return response()->json(['message' => 'Customer registered. OTP sent.', 'customer' => $customer]);
    }

    public function login(SendOtpRequest $request)
    {
        $phone = $request->input('phone');
        $customer = Customer::where('phone', $phone)->first();
        if (!$customer) {
            return response()->json(['message' => 'Customer not found.'], 404);
        }
        $this->otpService->generateOtp($phone);
        return response()->json(['message' => 'OTP sent.']);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully.']);
    }
}
