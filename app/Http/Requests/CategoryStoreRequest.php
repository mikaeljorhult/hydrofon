<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\PreserveReferer;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryStoreRequest extends FormRequest
{
    use PreserveReferer;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Category::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:60'],
            'parent_id' => ['nullable', Rule::exists('categories', 'id')],
            'groups.*' => [Rule::exists('groups', 'id')],
        ];
    }
}
