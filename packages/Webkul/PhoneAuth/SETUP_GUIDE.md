# PhoneAuth Package Integration Guide

This guide will help you integrate and test the new PhoneAuth package into your Bagisto installation.

## 1. Fix the "Nothing to migrate" Issue

If you're seeing "Nothing to migrate" when running migrations, follow these steps:

```bash
# Clear all Laravel caches
php artisan optimize:clear

# Register the package properly
php artisan package:discover

# Now run migrations
php artisan migrate
```

If the issue persists, you may need to manually run the migration files:

```bash
php artisan migrate --path=packages/Webkul/PhoneAuth/src/Database/Migrations
```

## 2. Test API Endpoints Manually

To test the API endpoints manually, you can use Postman or cURL. Here are the commands for testing:

### Register a new customer with phone number:
```bash
curl -X POST http://localhost:8000/api/phone-auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "+201234567890",
    "first_name": "Ahmed",
    "last_name": "Saeed"
  }'
```

### Send OTP to a phone number:
```bash
curl -X POST http://localhost:8000/api/phone-auth/send-otp \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "+201234567890"
  }'
```

### Verify OTP and get auth token:
```bash
curl -X POST http://localhost:8000/api/phone-auth/verify-otp \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "+201234567890",
    "otp": "1234"
  }'
```

### Login (send OTP to existing customer):
```bash
curl -X POST http://localhost:8000/api/phone-auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "+201234567890"
  }'
```

### Logout (protected route):
```bash
curl -X POST http://localhost:8000/api/phone-auth/logout \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## 3. Check Logs for OTP

Since you're using the 'log' SMS provider (as configured in config/phoneauth.php), you can check the Laravel logs to see the generated OTPs:

```bash
tail -f storage/logs/laravel.log | grep "SMS to"
```

## 4. Test with React.js

For your React.js frontend, use the Axios library to call these endpoints. Here's an example of how you might implement the login flow:

```javascript
import axios from 'axios';

// Send OTP
const sendOtp = async (phone) => {
  try {
    const response = await axios.post('/api/phone-auth/login', { phone });
    return response.data;
  } catch (error) {
    console.error(error);
    throw error;
  }
};

// Verify OTP
const verifyOtp = async (phone, otp) => {
  try {
    const response = await axios.post('/api/phone-auth/verify-otp', { phone, otp });
    
    // Save token to localStorage
    if (response.data.token) {
      localStorage.setItem('auth_token', response.data.token);
      axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
    }
    
    return response.data;
  } catch (error) {
    console.error(error);
    throw error;
  }
};

// Logout user
const logout = async () => {
  try {
    const response = await axios.post('/api/phone-auth/logout');
    localStorage.removeItem('auth_token');
    delete axios.defaults.headers.common['Authorization'];
    return response.data;
  } catch (error) {
    console.error(error);
    throw error;
  }
};
```

## 5. Automated Testing

You can run the automated test to ensure all endpoints are working correctly:

```bash
php artisan test tests/Feature/PhoneAuthTest.php
```

This will test register, send OTP, and verify OTP endpoints automatically.

## Troubleshooting

1. If routes are not working, check your RouteServiceProvider for API middleware configuration.
2. If token authentication fails, ensure Laravel Sanctum is properly configured.
3. For OTP issues, check your logs and the CustomerOtp table to ensure records are being created.

For any other issues, check the Laravel logs at `storage/logs/laravel.log`.
