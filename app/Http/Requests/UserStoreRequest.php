<?php

namespace Hydrofon\Http\Requests;

use Hydrofon\Http\Requests\Traits\PreserveReferer;
use Hydrofon\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
{
    use PreserveReferer;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'     => ['required'],
            'email'    => ['required', 'email', Rule::unique('users')],
            'password' => ['required', 'confirmed'],
            'groups.*' => [Rule::exists('groups', 'id')],
        ];
    }
}
