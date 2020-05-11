<?php

namespace App\Http\Requests;

use App\Identifier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class IdentifierStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Identifier::class);
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
                Rule::unique('identifiers'),
                Rule::unique('users', 'email'),
            ],
            'identifiable_type' => [
                'sometimes',
                'required',
                Rule::in(['resource', 'user']),
            ],
            'identifiable_id' => array_merge(['bail', 'sometimes'], (in_array($this->input('identifiable_type'), ['resource', 'user'])
                ? ['required', Rule::exists(Str::plural($this->input('identifiable_type')), 'id')]
                : []
            )),
        ];
    }
}
