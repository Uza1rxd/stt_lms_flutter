<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
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
            'user_id' => 'required',
            'course_id' => 'required',
            'qty' => 'required',
            'discount_type' => 'nullable',
            'discount_amount' => 'nullable',
            'course_price' => 'required',
            'description' => 'nullable',
            'payment_status' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'User ID is required.',
            'course_id.required' => 'Course ID is required.',
            'course_price.required' => 'Course price is required.',
        ];
    }
}
