<?php

namespace Hydrofon\Http\Requests;

use Hydrofon\Bucket;
use Illuminate\Foundation\Http\FormRequest;
use Hydrofon\Http\Requests\Traits\PreserveReferer;

class BucketStoreRequest extends FormRequest
{
    use PreserveReferer;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Bucket::class);
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
        ];
    }
}
