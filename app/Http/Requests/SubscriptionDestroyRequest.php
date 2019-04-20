<?php

namespace Hydrofon\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionDestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->isAdmin()) {
            return true;
        }

        return $this->user()->is($this->route('subscription')->subscribable);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
