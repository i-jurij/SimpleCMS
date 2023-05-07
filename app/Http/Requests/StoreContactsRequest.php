<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactsRequest extends FormRequest
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
            'type' => ['required', 'max:100'],
            'data' => 'required|unique:contacts|max:100',
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Hey! You have to fill in the :attribute field.',
            'data.required' => 'Hey! You have to fill in the :attribute field.',
        ];
    }

    public function attributes()
    {
        return [
            'type' => 'contacts type',
            'data' => 'contacts data',
        ];
    }
}
