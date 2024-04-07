<?php

namespace App\Http\Requests\Form;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormRequest extends FormRequest
{
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'boolean',
            'type' => 'string|max:20',
            'path' => 'file|mimes:jpg,jpeg,png,pdf,docx,xlsx|max:3072',
        ];
    }
}
