<?php

namespace Hydrofon\Http\Requests;

use Carbon\Carbon;
use Hydrofon\Http\Requests\Traits\PreserveReferer;
use Hydrofon\Rules\Available;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BookingUpdateRequest extends FormRequest
{
    use PreserveReferer;

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
        $this->conformDateFormat();
        $this->prepareApiFields();
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

    /**
     * Rename API fields and parse timestamps as UNIX timestamps.
     */
    protected function prepareApiFields()
    {
        $fields = collect(['resource_id' => 'resource', 'start_time' => 'start', 'end_time' => 'end'])
            ->filter(function ($apiField, $dbField) {
                return !$this->has($dbField) && $this->has($apiField);
            })
            ->mapWithKeys(function ($apiField, $dbField) {
                $value = $this->get($apiField);

                if (Str::contains($dbField, '_time')) {
                    $value = Carbon::parse('@'.$value);
                }

                return [$dbField => $value];
            });

        $this->merge($fields->toArray());
    }

    /**
     * Allow dates to be entered both with and without seconds.
     */
    protected function conformDateFormat()
    {
        $fields = collect(['start_time', 'end_time'])
            ->filter(function ($field) {
                return $this->has($field);
            })
            ->mapWithKeys(function ($field) {
                $value = $this->get($field);

                if ($value && preg_match('/\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}$/', $value)) {
                    return [$field => Carbon::parse($value.':00')];
                }

                return [$field => $value];
            });

        $this->merge($fields->toArray());
    }
}
