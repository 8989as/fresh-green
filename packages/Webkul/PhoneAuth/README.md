# Bagisto PhoneAuth Package

This package provides phone number + OTP authentication for Bagisto, replacing the default customer auth. It is designed for API use with a React.js frontend.

## Features
- Register/login with phone + OTP only
- OTP via SMS (Twilio or log)
- Laravel Sanctum API tokens
- Middleware for phone verification

## Installation
1. Add to `composer.json` and run `composer dump-autoload`
2. Run migrations: `php artisan migrate`
3. Publish config: `php artisan vendor:publish --tag=config`

## API Endpoints
- `POST /api/phone-auth/register` — Register new customer
- `POST /api/phone-auth/send-otp` — Send OTP to phone
- `POST /api/phone-auth/verify-otp` — Verify OTP and login
- `POST /api/phone-auth/login` — Send OTP for login
- `POST /api/phone-auth/logout` — Logout (auth:sanctum, phone.verified)

## Configuration
Edit `config/phoneauth.php` for OTP length, expiry, SMS provider, etc.

## License
MIT
