<?php

namespace Hydrofon\Http\Requests;

use Hydrofon\Booking;
use Hydrofon\Rules\Available;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Booking::class);
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
            'object_id'  => [
                'required',
                Rule::exists('objects', 'id'),
                new Available($this->input('start_time'), $this->input('end_time'))
            ],
            'start_time' => ['required', 'date', 'required_with:object_id', 'before:end_time'],
            'end_time'   => ['required', 'date', 'required_with:object_id', 'after:start_time'],
        ];
    }
}
