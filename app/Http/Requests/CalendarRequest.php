<?php

namespace Hydrofon\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
            'resources'   => ['nullable', 'array'],
            'resources.*' => ['nullable', Rule::exists('resources', 'id')],
        ];
    }
}
