<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'phone_number' => 'required|string|unique:users',
            'username' => 'required|string|max:255',
            'email' => 'required|string|unique:users|email',
            'password' => 'required|string|min:8|confirmed',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthdate' => 'required',
            'personal_photo'=>'required|image|mimes:png,jpg,jpeg|max:4096',
            'id_photo'=>'required|image|mimes:png,jpg,jpeg|max:4096'
        ];
    }
}
