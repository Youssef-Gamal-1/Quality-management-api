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
            'confirm-password' => 'required_with:password|same:password|max:255',
            'phone'=> 'string|max:11|min:11' ,
            'activated' => 'sometimes|boolean',
            'QM' => 'boolean',
            'SC' => 'boolean',
            'standard_id' => 'required_if:SC,true',
            'PC' => 'boolean',
            'program_id' => 'required_if:PC,true',
            'QU' => 'boolean',
            'EC' => 'boolean',
            'TS' => 'boolean',
            'programs' => 'required_if:Ts,true',
            'ST' => 'boolean'
        ];
    }
}
