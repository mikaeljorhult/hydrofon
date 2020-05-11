<?php

namespace App\Http\Requests\Traits;

use Illuminate\Contracts\Validation\Validator;

trait PreserveReferer
{
    /**
     * Make sure flashed referer is preserved if validation fails.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        if (session()->has('index-referer-url')) {
            session()->keep('index-referer-url');
        }

        parent::failedValidation($validator);
    }
}
