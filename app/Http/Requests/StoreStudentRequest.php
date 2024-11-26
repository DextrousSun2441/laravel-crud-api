<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize()
    {
        // Aquí iría tu lógica de autorización
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
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:student,email',
        'phone' => 'required|digits:10',
        'language' => 'required|in:English,Spanish,French',
    ];
}
}
