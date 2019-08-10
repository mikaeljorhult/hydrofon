<?php

namespace Hydrofon\Http\Requests;

use Hydrofon\Checkout;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Checkout::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'booking_id' => [Rule::exists('bookings', 'id')],
        ];
    }
}
