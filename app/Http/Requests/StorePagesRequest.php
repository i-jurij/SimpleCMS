<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePagesRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'alias' => ['required', 'unique:pages,alias', 'regex:/^[a-zA-Zа-яА-ЯёЁ0-9-_]{1,100}$/', 'max:100'],
            'title' => 'required|max:100',
            'description' => 'required|max:255',
        ];
    }

    public function messages()
    {
        return [
            'alias.required' => 'Warning! You have to fill in the :attribute field.',
            'title.required' => 'Warning! You have to fill in the :attribute field.',
            'description.required' => 'Warning! You have to fill in the :attribute field.',
        ];
    }

    public function attributes()
    {
        return [
            'alias' => 'pages alias',
            'title' => 'pages title',
            'description' => 'pages description',
        ];
    }
}
