<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\PreserveReferer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    use PreserveReferer;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('user'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->route('user')->id)],
            'password' => ['nullable', 'confirmed'],
            'groups.*' => [Rule::exists('groups', 'id')],
        ];
    }
}
