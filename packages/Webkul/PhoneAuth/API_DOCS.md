# PhoneAuth API Documentation

This document describes the API endpoints for the Bagisto PhoneAuth package. All endpoints are prefixed with `/api/phone-auth`.

---

## 1. Register Customer

**POST** `/api/phone-auth/register`

Registers a new customer and sends an OTP to the provided phone number.

### Request JSON
```json
{
  "phone": "+201234567890",
  "first_name": "Ahmed",
  "last_name": "Saeed"
}
```

### Response
- `201 Created` or `200 OK` on success
- Sends OTP to the phone

---

## 2. Send OTP

**POST** `/api/phone-auth/send-otp`

Sends an OTP to the provided phone number (for login or verification).

### Request JSON
```json
{
  "phone": "+201234567890"
}
```

### Response
- `200 OK` on success

---

## 3. Verify OTP

**POST** `/api/phone-auth/verify-otp`

Verifies the OTP and logs in the customer (returns API token).

### Request JSON
```json
{
  "phone": "+201234567890",
  "otp": "1234"
}
```

### Response
- `200 OK` with JSON:
```json
{
  "token": "<sanctum_token>",
  "customer": {
    "id": 1,
    "phone": "+201234567890",
    "phone_verified": true,
    ...
  }
}
```

---

## 4. Login (Send OTP for Existing Customer)

**POST** `/api/phone-auth/login`

Sends an OTP to the phone number if the customer exists.

### Request JSON
```json
{
  "phone": "+201234567890"
}
```

### Response
- `200 OK` on success
- `404 Not Found` if customer does not exist

---

## 5. Logout

**POST** `/api/phone-auth/logout`

Logs out the authenticated customer (requires `Authorization: Bearer <token>` header).

### Headers
```
Authorization: Bearer <sanctum_token>
```

### Response
- `200 OK` on success

---

## Error Responses
- All endpoints return JSON error messages with appropriate HTTP status codes.

---

## Notes
- All phone numbers must be in international format (e.g., `+201234567890`).
- OTP length and expiry are configurable in `config/phoneauth.php`.
- All endpoints are designed for API use with a React.js frontend.

---

For further customization or questions, see the main README or contact the package maintainer.
