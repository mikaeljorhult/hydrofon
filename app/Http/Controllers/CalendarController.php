<?php

namespace Hydrofon\Http\Controllers;

use Carbon\Carbon;
use Hydrofon\Http\Requests\CalendarRequest;
use Hydrofon\Resource;

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
     * @param null|string $date
     *
     * @return \Illuminate\Http\Response
     */
    public function index($date = null)
    {
        $date = $this->date($date)->startOfDay();
        $resources = $this->resources($date);
        $timestamps = $this->timestamps($date);

        return view('calendar')
            ->with('date', $date)
            ->with('resources', $resources)
            ->with('timestamps', $timestamps);
    }

    /**
     * Retrieve resources and redirect to calendar view.
     *
     * @param \Hydrofon\Http\Requests\CalendarRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CalendarRequest $request)
    {
        session()->put('resources', array_unique((array) $request->input('resources'), SORT_NUMERIC));

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
     * @param \Carbon\Carbon $date
     *
     * @return \Illuminate\Support\Collection
     */
    private function resources(Carbon $date)
    {
        return session()->has('resources')
            ? Resource::whereIn('id', session('resources'))
                // Eager-load bookings within requested date.
                      ->with([
                    'bookings' => function ($query) use ($date) {
                        $query
                            ->between($date, $date->copy()->endOfDay())
                            ->orderBy('start_time');
                    },
                ])
                      ->get()
            : collect();
    }

    /**
     * Build array of timestamps for use calendar.
     *
     * @param \Carbon\Carbon $date
     *
     * @return array
     */
    private function timestamps(Carbon $date)
    {
        $timestamps = [
            'start' => $date->format('U'),
            'end'   => $date->copy()->endOfDay()->format('U'),
        ];

        $timestamps['duration'] = $timestamps['end'] - $timestamps['start'];

        return $timestamps;
    }
}
