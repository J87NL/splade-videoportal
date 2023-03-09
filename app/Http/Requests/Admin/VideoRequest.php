<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use ProtoneMedia\Splade\FileUploads\HasSpladeFileUploads;

class VideoRequest extends FormRequest implements HasSpladeFileUploads
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
                Rule::unique('videos')->ignore($this->video->id ?? null)->whereNull('deleted_at'),
            ],
            'dance_id' => [
                'required',
                'exists:App\Models\Dance,id',
            ],
            'url' => [
                'nullable',
                'url',
            ],
            'image' => [
                'nullable',
                'file',
                'image',
            ],
            'videoPath' => [
                'nullable',
                'file',
            ],
        ];
    }
}
