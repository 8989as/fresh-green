<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Webkul\PhoneAuth\Models\Customer;
use Webkul\PhoneAuth\Models\CustomerOtp;

class PhoneAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_endpoint()
    {
        $response = $this->postJson('/api/phone-auth/register', [
            'phone' => '+201234567890',
            'first_name' => 'Test',
            'last_name' => 'User',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'customer'
        ]);

        $this->assertDatabaseHas('customers', [
            'phone' => '+201234567890',
            'first_name' => 'Test',
        ]);

        $this->assertDatabaseHas('customer_otps', [
            'phone' => '+201234567890',
        ]);
    }

    public function test_send_otp_endpoint()
    {
        $response = $this->postJson('/api/phone-auth/send-otp', [
            'phone' => '+201234567890',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['message']);

        $this->assertDatabaseHas('customer_otps', [
            'phone' => '+201234567890',
        ]);
    }

    public function test_verify_otp_endpoint()
    {
        // Create a customer
        $customer = Customer::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'phone' => '+201234567890',
            'phone_verified' => false,
        ]);

        // Create a manual OTP for testing
        $otp = CustomerOtp::create([
            'customer_id' => $customer->id,
            'phone' => '+201234567890',
            'otp' => '1234',
            'expires_at' => now()->addMinutes(5),
            'used' => false,
        ]);

        $response = $this->postJson('/api/phone-auth/verify-otp', [
            'phone' => '+201234567890',
            'otp' => '1234',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
            'customer'
        ]);

        $this->assertDatabaseHas('customer_otps', [
            'phone' => '+201234567890',
            'used' => true,
        ]);

        $this->assertDatabaseHas('customers', [
            'phone' => '+201234567890',
            'phone_verified' => true,
        ]);
    }
}
