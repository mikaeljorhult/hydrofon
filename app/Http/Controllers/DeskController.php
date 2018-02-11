<?php

namespace Hydrofon\Http\Controllers;

use Hydrofon\Http\Requests\DeskRequest;
use Hydrofon\User;

class DeskController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the circulation desk view.
     *
     * @param null|string $search
     *
     * @return \Illuminate\Http\Response
     */
    public function index($search = null)
    {
        // Only resolve user if a search string is available.
        $user = $search ? $this->resolveUser($search) : null;

        // Get all bookings 4 days in the past and in the future.
        $bookings = $user ? $user->bookings()->where(function ($query) {
            $query->between(now()->subDays(4), now()->addDays(4))
                  ->orderBy('start_time', 'DESC');
        })->orderByField(request()->get('order', 'start_time'))->paginate(15) : collect();

        return view('desk')
            ->with('search', $search)
            ->with('user', $user)
            ->with('bookings', $bookings);
    }

    /**
     * Redirect to desk view with search term.
     *
     * @param \Hydrofon\Http\Requests\DeskRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(DeskRequest $request)
    {
        return redirect('/desk/'.$request->input('search'));
    }

    /**
     * Resolve user from search term.
     * Checks against user e-mail address and otherwise against identifiers.
     *
     * @param $search
     *
     * @return mixed
     */
    private function resolveUser($search)
    {
        return User::where('email', $search)
                   ->orWhereHas('identifiers', function ($query) use ($search) {
                       $query->where('value', $search);
                   })->first();
    }
}
