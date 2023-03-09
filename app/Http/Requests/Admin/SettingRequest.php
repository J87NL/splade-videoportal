<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingRequest extends FormRequest
{
    public function authorize(Request $request)
    {
        return $request->user()->is_admin;
    }

    public function rules(): array
    {
        return [
            'key' => [
                'required',
                'string',
                'max:255',
                Rule::unique('settings')->ignore($this->setting->id ?? null),
            ],
            'value' => [
                'nullable',
                'string',
            ],
        ];
    }
}
