<?php

namespace Webkul\Contact\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LandscapeRequestFormRequest extends FormRequest
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
            'city' => 'required|string|max:255',
            'landscape_area' => 'required|string|max:255',
            'message' => 'nullable|string',
        ];
    }
}
