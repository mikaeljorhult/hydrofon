<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('category'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:60'],
            'parent_id' => ['nullable', Rule::notIn($this->route('category')->id), Rule::exists('categories', 'id')],
            'groups.*' => [Rule::exists('groups', 'id')],
        ];
    }
}
