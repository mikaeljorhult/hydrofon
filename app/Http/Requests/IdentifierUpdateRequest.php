<?php

namespace Hydrofon\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
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
                'required',
                Rule::unique('identifiers')->ignore($this->route('identifier')->id),
                Rule::unique('users', 'email'),
            ],
        ];
    }
}
