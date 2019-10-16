<?php

namespace Hydrofon\Http\Requests;

use Hydrofon\Http\Requests\Traits\PreserveReferer;
use Illuminate\Foundation\Http\FormRequest;

class BucketDestroyRequest extends FormRequest
{
    use PreserveReferer;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('delete', $this->route('bucket'));
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
