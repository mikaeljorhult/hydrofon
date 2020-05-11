<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CalendarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date'        => ['nullable', 'date'],
            'categories'   => ['nullable', 'array'],
            'categories.*' => ['nullable', Rule::exists('categories', 'id')],
            'resources'   => ['nullable', 'array'],
            'resources.*' => ['nullable', Rule::exists('resources', 'id')],
        ];
    }
}
