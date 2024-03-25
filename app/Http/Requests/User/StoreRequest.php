<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => 'required|string|max:255|min:5',
            'academic_email' => 'required|email|unique:users,academic_email',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|max:255|min:5|unique:users,username',
            'password' => 'required|max:255',
            'confirm-password' => 'required|same:password',
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
