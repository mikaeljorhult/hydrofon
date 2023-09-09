<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarRequest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
     */
    public function index(string $date = null): View
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
     */
    public function store(CalendarRequest $request): RedirectResponse
    {
        session()->put('expanded', array_unique((array) $request->input('categories'), SORT_NUMERIC));
        session()->flash('resources', array_unique((array) $request->input('resources'), SORT_NUMERIC));

        return redirect('/calendar/'.$request->input('date'));
    }

    /**
     * Parse supplied date or default to current date.
     */
    private function date($date): Carbon
    {
        return $date != null
            ? Carbon::parse($date)
            : now();
    }

    /**
     * Return resources stored in session.
     */
    private function resources(): array
    {
        return session('resources', []);
    }

    /**
     * Return categories stored in session.
     */
    private function categories(): array
    {
        return session('expanded', []);
    }
}
