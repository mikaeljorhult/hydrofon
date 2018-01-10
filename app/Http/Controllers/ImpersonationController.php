<?php

namespace Hydrofon\Http\Controllers;

use Hydrofon\User;
use Illuminate\Http\Request;

class ImpersonationController extends Controller
{
    /**
     * Create impersonation of user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = User::findOrFail($request->get('user_id'));

        // Administrators can't be impersonated.
        if ( ! $user->isAdmin()) {
            session()->put('impersonate', $user->id);
        }

        return redirect()->back();
    }

    /**
     * Stop impersonation of user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        session()->forget('impersonate');

        return redirect()->back();
    }
}
