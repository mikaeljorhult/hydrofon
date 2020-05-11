<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImpersonationRequest;
use App\User;

class ImpersonationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin')->except('destroy');
    }

    /**
     * Create impersonation of user.
     *
     * @param \App\Http\Requests\ImpersonationRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ImpersonationRequest $request)
    {
        $user = User::findOrFail($request->get('user_id'));

        // Administrators can't be impersonated.
        if (! $user->isAdmin()) {
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
