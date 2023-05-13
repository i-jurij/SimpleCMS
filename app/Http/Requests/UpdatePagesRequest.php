<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdatePagesRequest extends StorePagesRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'alias' => [
                Rule::unique('pages')->ignore($this->id),
            ],
        ]);
    }
}
