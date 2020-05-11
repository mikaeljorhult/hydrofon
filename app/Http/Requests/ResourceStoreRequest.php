<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\PreserveReferer;
use App\Resource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResourceStoreRequest extends FormRequest
{
    use PreserveReferer;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Resource::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'         => ['required', 'max:60'],
            'categories.*' => [Rule::exists('categories', 'id')],
            'groups.*'     => [Rule::exists('groups', 'id')],
        ];
    }
}
