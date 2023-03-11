<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImpersonationRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

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
     */
    public function store(ImpersonationRequest $request): RedirectResponse
    {
        $user = User::findOrFail($request->get('user_id'));

        // Administrators can't be impersonated.
        if (! $user->isAdmin()) {
            session()->put('impersonate', $user->id);
            session()->put('impersonated_by', auth()->id());
        }

        return redirect()->route('calendar');
    }

    /**
     * Stop impersonation of user.
     */
    public function destroy(): RedirectResponse
    {
        session()->forget('impersonate');

        return redirect()->back();
    }
}
