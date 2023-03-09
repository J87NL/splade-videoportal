<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserUpdateRequest extends FormRequest
{
    public function authorize(Request $request)
    {
        return $request->user()->is_admin;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'is_admin' => [
                'required',
                'boolean',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user->id)->whereNull('deleted_at'),
            ],
            'password' => [
                'nullable',
                'confirmed',
                Password::defaults(),
            ],
            'sportivity_customer_id' => [
                'nullable',
            ],
        ];
    }
}
