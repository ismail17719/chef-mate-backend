<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyIdRequest extends FormRequest
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
            'front_image' => 'required|extensions:png,jpeg',
            'back_image' => 'required|extensions:png,jpeg',
        ];
    }

    public function messages(){
        return [
            'front_image.required' => 'Image Required or Image size must be less than 1MB',
            'front_image.extensions' => 'Upload image in png,jpg,jpeg',
            'back_image.required' => 'Image Required or Image size must be less than 1MB',
            'back_image.extensions' => 'Upload image in png,jpg,jpeg',
        ];
    }
}
