<?php

namespace Webkul\Contact\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactUsFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'nullable|string',
        ];
    }
}
