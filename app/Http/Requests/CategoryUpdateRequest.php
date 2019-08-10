<?php

namespace Hydrofon\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Hydrofon\Http\Requests\Traits\PreserveReferer;

class CategoryUpdateRequest extends FormRequest
{
    use PreserveReferer;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('category'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'      => ['required', 'max:60'],
            'parent_id' => ['nullable', Rule::notIn($this->route('category')->id), Rule::exists('categories', 'id')],
            'groups.*'  => [Rule::exists('groups', 'id')],
        ];
    }
}
