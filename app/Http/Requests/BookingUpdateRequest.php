<?php

namespace Hydrofon\Http\Requests;

use Carbon\Carbon;
use Hydrofon\Rules\Available;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
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
        return $this->user()->can('update', $this->route('booking'));
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $fields = collect(['resource_id' => 'resource', 'start_time' => 'start', 'end_time' => 'end'])
            ->filter(function ($apiField, $dbField) {
                return !$this->has($dbField) && $this->has($apiField);
            })
            ->map(function ($apiField, $dbField) {
                $value = $this->get($apiField);

                if (Str::contains($dbField, '_time')) {
                    $value = Carbon::parse('@'.$this->get($apiField));
                }

                return $value;
            });

        $this->merge($fields->toArray());
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
                new Available($this->input('start_time'), $this->input('end_time'), $this->route('booking')->id),
            ],
            'start_time'  => ['required', 'date', 'required_with:resource_id', 'before:end_time'],
            'end_time'    => ['required', 'date', 'required_with:resource_id', 'after:start_time'],
        ];
    }
}
