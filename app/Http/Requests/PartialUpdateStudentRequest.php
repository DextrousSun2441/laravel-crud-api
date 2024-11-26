<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartialUpdateStudentRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => 'sometimes|max:255|string',
            'email' => 'sometimes|email|unique:students,email,' . $this->route('id'),
            'phone' => 'sometimes|digits:10|string',
            'language' => 'sometimes|in:English,Spanish,French|string',
        ];
    }
}
