<?php

namespace Hydrofon\Http\Requests;

use Hydrofon\Object;
use Illuminate\Foundation\Http\FormRequest;

class ObjectStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Object::class);
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
        ];
    }
}
