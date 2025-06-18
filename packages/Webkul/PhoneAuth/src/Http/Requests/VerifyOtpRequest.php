<?php

namespace Webkul\PhoneAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'regex:/^\+?[0-9]{10,15}$/'],
            'otp' => ['required', 'string', 'size:' . config('phoneauth.otp_length')],
        ];
    }
}
