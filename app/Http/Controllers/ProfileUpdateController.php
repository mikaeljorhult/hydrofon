<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;

class ProfileUpdateController extends Controller
{
    public function __invoke(ProfileUpdateRequest $request)
    {
        $input = $request->validated();

        auth()->user()->update($input);

        flash(json_encode([
            'title' => 'Profile was updated',
            'message' => 'Profile was updated successfully.',
        ]), 'success');

        return redirect()->route('profile.index');
    }
}
