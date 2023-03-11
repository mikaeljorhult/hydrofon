<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CalendarRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'date' => ['nullable', 'date'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['nullable', Rule::exists('categories', 'id')],
            'resources' => ['nullable', 'array'],
            'resources.*' => ['nullable', Rule::exists('resources', 'id')],
        ];
    }
}
