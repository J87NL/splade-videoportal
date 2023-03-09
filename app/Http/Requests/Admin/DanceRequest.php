<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DanceRequest extends FormRequest
{
    public function authorize(Request $request)
    {
        return $request->user()->is_admin;
    }

    public function rules(): array
    {
        return [
            'position' => [
                'integer',
            ],
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('dances')->ignore($this->dance->id ?? null)->whereNull('deleted_at'),
            ],
            'dancetype_id' => [
                'integer',
            ],
        ];
    }
}
