<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfferBannerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'is_active' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'thumbnail.image' => 'The banner image must be an image file.',
            'thumbnail.mimes' => 'The banner image must be a JPEG, PNG, or JPG file.',
            'thumbnail.max' => 'The banner image size must not exceed 2MB.',
            'title.required' => 'The banner title is required.',
            'title.string' => 'The banner title must be a string.'
        ];
    }
}
