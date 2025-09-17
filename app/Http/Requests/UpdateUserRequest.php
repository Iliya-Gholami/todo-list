<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'min:3', 'max:70'],
            'password' => ['nullable', 'min:8', 'max:16'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users'],
            'birthday' => ['nullable', 'date', 'before:today', 'date_format:Y-m-d']
        ];
    }
}
