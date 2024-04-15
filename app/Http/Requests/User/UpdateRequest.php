<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255|min:5',
            'academic_email' => 'sometimes|email|unique:users,academic_email',
            'email' => 'sometimes|email|unique:users,email',
            'username' => 'sometimes|max:255|min:5|unique:users,username',
            'password' => 'sometimes|max:255',
            'confirm-password' => 'sometimes|same:password',
            'phone'=> 'string|max:11|min:11' ,
            'QM' => 'boolean',
            'SC' => 'boolean',
            'PC' => 'boolean',
            'QU' => 'boolean',
            'EC' => 'boolean',
            'TS' => 'boolean',
        ];
    }
}
