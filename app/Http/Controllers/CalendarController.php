<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarRequest;
use Carbon\Carbon;

class CalendarController extends Controller
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
     * Show the calendar view.
     *
     * @param  null|string  $date
     * @return \Illuminate\Http\Response
     */
    public function index($date = null)
    {
        $date = $this->date($date)->startOfDay();
        $expanded = $this->categories();
        $resources = $this->resources();

        return view('calendar')
            ->with('date', $date)
            ->with('expanded', $expanded)
            ->with('resources', $resources);
    }

    /**
     * Retrieve resources and redirect to calendar view.
     *
     * @param  \App\Http\Requests\CalendarRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CalendarRequest $request)
    {
        session()->put('expanded', array_unique((array) $request->input('categories'), SORT_NUMERIC));
        session()->flash('resources', array_unique((array) $request->input('resources'), SORT_NUMERIC));

        return redirect('/calendar/'.$request->input('date'));
    }

    /**
     * Parse supplied date or default to current date.
     *
     * @param $date
     * @return \Carbon\Carbon
     */
    private function date($date)
    {
        return $date != null
            ? Carbon::parse($date)
            : now();
    }

    /**
     * Return resources stored in session.
     *
     * @return array
     */
    private function resources()
    {
        return session('resources', []);
    }

    /**
     * Return categories stored in session.
     *
     * @return array
     */
    private function categories()
    {
        return session('expanded', []);
    }
}
