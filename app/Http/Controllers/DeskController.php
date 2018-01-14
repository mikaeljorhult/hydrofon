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
        $user = $this->resolveUser($search);

        $user->load([
            'bookings' => function ($query) {
                $query->between(now()->subDays(4), now()->addDays(4))
                      ->orderBy('start_time', 'DESC');
            }
        ]);

        return view('desk')
            ->with('search', $search)
            ->with('user', $user);
    }

    /**
     * Retrieve user and redirect to desk view.
     *
     * @param \Hydrofon\Http\Requests\DeskRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(DeskRequest $request)
    {
        return redirect('/desk/' . $request->input('search'));
    }

    /**
     * @param $search
     *
     * @return mixed
     */
    private function resolveUser($search)
    {
        return User::where('email', $search)->first();
    }
}
