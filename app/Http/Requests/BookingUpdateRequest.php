<?php

namespace Hydrofon\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingUpdateRequest extends FormRequest
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
            'user_id'    => ['sometimes', 'nullable', Rule::exists('users', 'id')],
            'object_id'  => ['required', Rule::exists('objects', 'id')],
            'start_time' => ['required', 'date', 'before:end_time'],
            'end_time'   => ['required', 'date', 'after:start_time'],
        ];
    }
}
