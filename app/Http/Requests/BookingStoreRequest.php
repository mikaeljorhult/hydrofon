<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use App\Booking;
use App\Http\Requests\Traits\PreserveReferer;
use App\Rules\Available;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BookingStoreRequest extends FormRequest
{
    use PreserveReferer;

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
            'user_id'     => ['sometimes', 'nullable', Rule::exists('users', 'id')],
            'resource_id' => [
                'required',
                Rule::exists('resources', 'id'),
                new Available($this->input('start_time'), $this->input('end_time')),
            ],
            'start_time'  => ['required', 'date', 'required_with:resource_id', 'before:end_time'],
            'end_time'    => ['required', 'date', 'required_with:resource_id', 'after:start_time'],
        ];
    }
}
