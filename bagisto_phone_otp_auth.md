# Custom Phone OTP Authentication Package for Bagisto

## Overview
This guide will help you create a custom authentication package for Bagisto that uses phone number and OTP verification as the default authentication system, with React.js frontend integration.

## Package Structure

### 1. Create Package Directory Structure
```
packages/Webkul/PhoneAuth/
├── src/
│   ├── Config/
│   │   └── phoneauth.php
│   ├── Database/
│   │   ├── Migrations/
│   │   │   ├── 2024_01_01_000001_create_customer_otps_table.php
│   │   │   └── 2024_01_01_000002_modify_customers_table.php
│   │   └── Seeders/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       └── AuthController.php
│   │   ├── Middleware/
│   │   │   └── PhoneVerified.php
│   │   └── Requests/
│   │       ├── SendOtpRequest.php
│   │       ├── VerifyOtpRequest.php
│   │       └── RegisterRequest.php
│   ├── Models/
│   │   ├── CustomerOtp.php
│   │   └── Customer.php
│   ├── Repositories/
│   │   └── CustomerOtpRepository.php
│   ├── Services/
│   │   ├── OtpService.php
│   │   └── SmsService.php
│   ├── Providers/
│   │   └── PhoneAuthServiceProvider.php
│   └── Routes/
│       └── api.php
├── composer.json
```

## Implementation Steps

### 2. Composer Configuration
Create `composer.json`:
```json
{
    "name": "Webkul/bagisto-phone-auth",
    "description": "Phone OTP Authentication Package for Bagisto",
    "type": "library",
    "require": {
        "php": "^8.1",
        "laravel/framework": "^10.0",
        "bagisto/bagisto": "^2.3"
    },
    "autoload": {
        "psr-4": {
            "Webkul\\PhoneAuth\\": "src/"
        }
    },
    "extra": {
        "laravel": {
            "providers": [
                "Webkul\\PhoneAuth\\Providers\\PhoneAuthServiceProvider"
            ]
        }
    }
}
```

### 3. Service Provider
<?php

namespace Webkul\PhoneAuth\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\PhoneAuth\Models\Customer;
use Illuminate\Foundation\AliasLoader;

class PhoneAuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        
        $this->publishes([
            __DIR__ . '/../Config/phoneauth.php' => config_path('phoneauth.php'),
        ], 'config');

        // Override Bagisto's Customer model
        $this->app->bind(
            \Webkul\Customer\Models\Customer::class,
            Customer::class
        );
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/phoneauth.php', 'phoneauth');
        
        $this->app->register(\Webkul\Customer\Providers\CustomerServiceProvider::class);
    }
}
```

### 4. Configuration File
Create `src/Config/phoneauth.php`:
```php
<?php

return [
    'otp_length' => 4, // Length of the OTP
    'otp_expiry' => 5, // minutes
    'max_attempts' => 3,
    'sms_provider' => env('SMS_PROVIDER', 'twilio'), // twilio, nexmo, custom
    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_TOKEN'),
        'from' => env('TWILIO_FROM'),
    ],
    'rate_limiting' => [
        'send_otp' => '5,1', // 5 attempts per minute
        'verify_otp' => '10,1', // 10 attempts per minute
    ],
];
```

### 5. Database Migrations
Create `src/Database/Migrations/2024_01_01_000001_create_customer_otps_table.php`:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_otps', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number', 20)->index();
            $table->string('otp', 10);
            $table->timestamp('expires_at');
            $table->integer('attempts')->default(0);
            $table->boolean('verified')->default(false);
            $table->timestamps();
            
            $table->index(['phone_number', 'verified']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_otps');
    }
};
```

Create `src/Database/Migrations/2024_01_01_000002_modify_customers_table.php`:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('phone_verified')->default(false)->after('phone');
            $table->timestamp('phone_verified_at')->nullable()->after('phone_verified');
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['phone_verified', 'phone_verified_at']);
        });
    }
};
```

### 6. Models
Create `src/Models/CustomerOtp.php`:
```php
<?php

namespace Webkul\PhoneAuth\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CustomerOtp extends Model
{
    protected $fillable = [
        'phone_number',
        'otp',
        'expires_at',
        'attempts',
        'verified'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified' => 'boolean',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return !$this->verified && !$this->isExpired() && $this->attempts < config('phoneauth.max_attempts');
    }

    public function incrementAttempts(): void
    {
        $this->increment('attempts');
    }

    public function markAsVerified(): void
    {
        $this->update(['verified' => true]);
    }
}
```

Create `src/Models/Customer.php` (extends Bagisto's Customer):
```php
<?php

namespace Webkul\PhoneAuth\Models;

use Webkul\Customer\Models\Customer as BaseCustomer;

class Customer extends BaseCustomer
{
    protected $fillable = [
    'first_name',
    'last_name',
    'gender',
    'date_of_birth',
    'email',
    'phone',
    'image',
    'status',
    'password',
    'api_token',
    'customer_group_id',
    'subscribed_to_news_letter',
    'is_verified',
    'is_suspended',
    'token',
    'notes'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_verified' => 'boolean',
        'phone_verified' => 'boolean',
        'phone_verified_at' => 'datetime',
    ];

    public function markPhoneAsVerified(): void
    {
        $this->forceFill([
            'phone_verified' => true,
            'phone_verified_at' => now(),
        ])->save();
    }

    public function hasVerifiedPhone(): bool
    {
        return $this->phone_verified;
    }

    // Override to use phone as username
    public function findForPassport($username)
    {
        return $this->where('phone', $username)
                   ->where('phone_verified', true)
                   ->first();
    }
}
```

### 7. Services
Create `src/Services/OtpService.php`:
```php
<?php

namespace Webkul\PhoneAuth\Services;

use Webkul\PhoneAuth\Models\CustomerOtp;
use Webkul\PhoneAuth\Services\SmsService;
use Carbon\Carbon;

class OtpService
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function generateOtp(string $phoneNumber): string
    {
        // Clean previous OTPs for this phone number
        CustomerOtp::where('phone_number', $phoneNumber)->delete();

        $otp = $this->generateRandomOtp();
        $expiresAt = Carbon::now()->addMinutes(config('phoneauth.otp_expiry'));

        CustomerOtp::create([
            'phone_number' => $phoneNumber,
            'otp' => $otp,
            'expires_at' => $expiresAt,
        ]);

        return $otp;
    }

    public function sendOtp(string $phoneNumber): bool
    {
        $otp = $this->generateOtp($phoneNumber);
        $message = "Your verification code is: {$otp}. Valid for " . config('phoneauth.otp_expiry') . " minutes.";
        
        return $this->smsService->send($phoneNumber, $message);
    }

    public function verifyOtp(string $phoneNumber, string $otp): bool
    {
        $otpRecord = CustomerOtp::where('phone_number', $phoneNumber)
            ->where('verified', false)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$otpRecord || !$otpRecord->isValid()) {
            return false;
        }

        if ($otpRecord->otp !== $otp) {
            $otpRecord->incrementAttempts();
            return false;
        }

        $otpRecord->markAsVerified();
        return true;
    }

    protected function generateRandomOtp(): string
    {
        $length = config('phoneauth.otp_length');
        return str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }
}
```

Create `src/Services/SmsService.php`:
```php
<?php

namespace Webkul\PhoneAuth\Services;

use Twilio\Rest\Client as TwilioClient;
use Exception;

class SmsService
{
    protected $twilioClient;

    public function __construct()
    {
        if (config('phoneauth.sms_provider') === 'twilio') {
            $this->twilioClient = new TwilioClient(
                config('phoneauth.twilio.sid'),
                config('phoneauth.twilio.token')
            );
        }
    }

    public function send(string $phoneNumber, string $message): bool
    {
        try {
            switch (config('phoneauth.sms_provider')) {
                case 'twilio':
                    return $this->sendViaTwilio($phoneNumber, $message);
                default:
                    throw new Exception('SMS provider not configured');
            }
        } catch (Exception $e) {
            \Log::error('SMS sending failed: ' . $e->getMessage());
            return false;
        }
    }

    protected function sendViaTwilio(string $phoneNumber, string $message): bool
    {
        $this->twilioClient->messages->create($phoneNumber, [
            'from' => config('phoneauth.twilio.from'),
            'body' => $message
        ]);

        return true;
    }
}
```

### 8. API Controller
Create `src/Http/Controllers/Api/AuthController.php`:
```php
<?php

namespace Webkul\PhoneAuth\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Webkul\Core\Http\Controllers\Controller;
use Webkul\PhoneAuth\Models\Customer;
use Webkul\PhoneAuth\Services\OtpService;
use Webkul\PhoneAuth\Http\Requests\SendOtpRequest;
use Webkul\PhoneAuth\Http\Requests\VerifyOtpRequest;
use Webkul\PhoneAuth\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function sendOtp(SendOtpRequest $request): JsonResponse
    {
        $phoneNumber = $request->phone_number;
        $key = 'send-otp:' . $phoneNumber;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many OTP requests. Please try again later.',
            ], 429);
        }

        RateLimiter::hit($key, 60); // 1 minute

        $sent = $this->otpService->sendOtp($phoneNumber);

        return response()->json([
            'success' => $sent,
            'message' => $sent ? 'OTP sent successfully' : 'Failed to send OTP',
        ]);
    }

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $phoneNumber = $request->phone_number;
        $otp = $request->otp;
        $key = 'verify-otp:' . $phoneNumber;

        if (RateLimiter::tooManyAttempts($key, 10)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many verification attempts. Please try again later.',
            ], 429);
        }

        RateLimiter::hit($key, 60);

        $verified = $this->otpService->verifyOtp($phoneNumber, $otp);

        if (!$verified) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully',
        ]);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        // First verify OTP
        $verified = $this->otpService->verifyOtp($request->phone_number, $request->otp);
        
        if (!$verified) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP',
            ], 400);
        }

        $customer = Customer::create([
            'phone' => $request->phone_number,
            'phone_verified' => true,
            'phone_verified_at' => now(),
            'is_verified' => true,
            'customer_group_id' => 1,
        ]);

        $token = $customer->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'customer' => $customer,
                'token' => $token,
            ],
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|string',
        ]);

        $verified = $this->otpService->verifyOtp($request->phone_number, $request->otp);
        
        if (!$verified) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP',
            ], 400);
        }

        $customer = Customer::where('phone', $request->phone)
            ->where('phone_verified', true)
            ->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
            ], 404);
        }

        $token = $customer->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'customer' => $customer,
                'token' => $token,
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }
}
```

### 9. Form Requests
Create `src/Http/Requests/SendOtpRequest.php`:
```php
<?php

namespace Webkul\PhoneAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => 'required|string|regex:/^\+?[0-9]{9,}$/'
        ];
    }

    public function messages(): array
    {
        return [
            'phone_number.regex' => 'Please enter a valid phone number.',
        ];
    }
}
```

Create similar request files for `VerifyOtpRequest.php` and `RegisterRequest.php`.

### 10. API Routes
Create `src/Routes/shop-routes.php`:
```php
<?php

use Illuminate\Support\Facades\Route;
use Webkul\PhoneAuth\Http\Controllers\Api\AuthController;

Route::prefix('api/phone-auth')->group(function () {
    Route::post('send-otp', [AuthController::class, 'sendOtp']);
    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
    });
});
```