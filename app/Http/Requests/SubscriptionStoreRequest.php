<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SubscriptionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if ($this->user()->isAdmin()) {
            return true;
        }

        return $this->get('subscribable_type') === 'user' && $this->get('subscribable_id') === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'subscribable_type' => [
                'required',
                Rule::in(['resource', 'user']),
            ],
            'subscribable_id' => ['required'],
        ];

        if (in_array($this->input('subscribable_type'), ['resource', 'user'])) {
            $rules['subscribable_id'][] = Rule::exists(Str::plural($this->get('subscribable_type')), 'id');
        }

        return $rules;
    }
}
