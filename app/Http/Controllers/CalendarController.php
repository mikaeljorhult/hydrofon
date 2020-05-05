<?php

namespace Hydrofon\Http\Controllers;

use Carbon\Carbon;
use Hydrofon\Http\Requests\CalendarRequest;

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
     *
     * @return \Illuminate\Http\Response
     */
    public function index($date = null)
    {
        $date = $this->date($date)->startOfDay();
        $timestamps = $this->timestamps($date);
        $expanded = $this->categories();
        $resources = $this->resources();

        return view('calendar')
            ->with('date', $date)
            ->with('expanded', $expanded)
            ->with('resources', $resources)
            ->with('timestamps', $timestamps);
    }

    /**
     * Retrieve resources and redirect to calendar view.
     *
     * @param  \Hydrofon\Http\Requests\CalendarRequest  $request
     *
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
     *
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

    /**
     * Build array of timestamps for use calendar.
     *
     * @param  \Carbon\Carbon  $date
     *
     * @return array
     */
    private function timestamps(Carbon $date)
    {
        $timestamps = [
            'current' => (int) now()->format('U'),
            'start'   => (int) $date->format('U'),
            'end'     => (int) $date->copy()->endOfDay()->format('U'),
        ];

        $timestamps['duration'] = $timestamps['end'] - $timestamps['start'];

        return $timestamps;
    }
}
