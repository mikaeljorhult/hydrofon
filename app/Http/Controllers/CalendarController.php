<?php

namespace Hydrofon\Http\Controllers;

use Carbon\Carbon;
use Hydrofon\Http\Requests\CalendarRequest;
use Hydrofon\Object;

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
        $date    = $this->date($date)->startOfDay();
        $objects = $this->objects();

        // Eager-load bookings if there are objects.
        if ($objects->count() > 0) {
            $objects->load([
                'bookings' => function ($query) use ($date) {
                    $query
                        ->between($date, $date->copy()->endOfDay())
                        ->orderBy('start_time');
                }
            ]);
        }

        return view('calendar')
            ->with('date', $date)
            ->with('objects', $objects);
    }

    /**
     * Retrieve objects and redirect to calendar view.
     *
     * @param \Hydrofon\Http\Requests\CalendarRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CalendarRequest $request)
    {
        session()->put('objects', array_unique($request->input('objects'), SORT_NUMERIC));

        return redirect('/calendar/' . $request->input('date'));
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
     * Return objects stored in session.
     *
     * @return \Illuminate\Support\Collection
     */
    private function objects()
    {
        return session()->has('objects')
            ? Object::whereIn('id', session('objects'))->get()
            : collect();
    }
}
