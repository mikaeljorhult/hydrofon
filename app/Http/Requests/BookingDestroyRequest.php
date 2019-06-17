<?php

namespace Hydrofon\Http\Requests;

use Hydrofon\Http\Requests\Traits\PreserveReferer;
use Illuminate\Foundation\Http\FormRequest;

class BookingDestroyRequest extends FormRequest
{
    use PreserveReferer;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('delete', $this->route('booking'));
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
