<?php

namespace Hydrofon\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class IdentifierUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('identifier'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // TODO: Validation should fail for __ANY__ e-mail address.
        return [
            'value' => [
                'sometimes',
                'required',
                Rule::unique('identifiers')->ignore($this->route('identifier')->id),
                Rule::unique('users', 'email'),
            ],
            'identifiable_type' => [
                'required_without:value',
                'required_with:identifiable_id',
                Rule::in(['resource', 'user']),
            ],
            'identifiable_id' => array_merge(['bail', 'required_without:value', 'required_with:identifiable_type'], (in_array($this->input('identifiable_type'), ['resource', 'user'])
                ? [Rule::exists(Str::plural($this->input('identifiable_type')), 'id')]
                : []
            )),
        ];
    }
}
