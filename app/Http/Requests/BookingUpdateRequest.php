<?php

namespace App\Http\Requests;

use App\Rules\Available;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('booking'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_id' => ['sometimes', 'nullable', Rule::exists('users', 'id')],
            'resource_id' => [
                'required',
                Rule::exists('resources', 'id'),
                new Available($this->input('start_time'), $this->input('end_time'), $this->route('booking')->id),
            ],
            'start_time' => ['required', 'date', 'required_with:resource_id', 'before:end_time'],
            'end_time' => ['required', 'date', 'required_with:resource_id', 'after:start_time'],
        ];
    }
}
