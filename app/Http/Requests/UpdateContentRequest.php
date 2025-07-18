<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'body' => 'sometimes|string',
            'allowed_countries' => 'sometimes|array',
            'allowed_countries.*' => 'string|size:2',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
