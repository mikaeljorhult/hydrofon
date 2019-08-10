<?php

namespace Hydrofon\Http\Controllers;

use Hydrofon\User;
use Hydrofon\Identifier;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Hydrofon\Http\Requests\DeskRequest;

class DeskController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
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
        // Only resolve resource or user if a search string is available.
        $identifiable = $search ? $this->resolveIdentifiable($search) : null;

        // Get bookings for resource/user or empty collection.
        $bookings = $identifiable
            ? $this->getBookings($identifiable)
            : collect();

        return view('desk')
            ->with('search', $search)
            ->with('identifiable', $identifiable)
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
     * Resolve resource or user from search term.
     * Checks against user e-mail address and otherwise against identifiers.
     *
     * @param $search
     *
     * @return mixed
     */
    private function resolveIdentifiable($search)
    {
        $identifiable = User::where('email', $search)->first();

        if (! $identifiable) {
            $identifier = Identifier::with('identifiable')
                                      ->where('value', $search)
                                      ->first();

            $identifiable = optional($identifier)->identifiable;
        }

        return $identifiable;
    }

    /**
     * Get bookings of identifiable.
     *
     * @param \Hydrofon\Resource|\Hydrofon\User $identifiable
     *
     * @return mixed
     */
    private function getBookings($identifiable)
    {
        return QueryBuilder::for($identifiable->bookings()->getQuery())
                           ->select('bookings.*')
                           ->with(['checkin', 'checkout', 'resource', 'user'])
                           ->whereDoesntHave('checkin')
                           ->whereHas('resource', function ($query) {
                               $query->where('is_facility', '=', 0);
                           })
                           ->where(function ($query) {
                               $filter = request()->query->get('filter');

                               // Set default time span to +/- 4 days if not in request.
                               if (! isset($filter['between'])) {
                                   $query->between(now()->subDays(4), now()->addDays(4));
                               }
                           })
                           ->join('resources', 'resources.id', '=', 'bookings.resource_id')
                           ->join('users', 'users.id', '=', 'bookings.user_id')
                           ->allowedFilters(Filter::scope('between'))
                           ->defaultSort('start_time')
                           ->allowedSorts(['resources.name', 'users.name', 'start_time', 'end_time'])
                           ->paginate(15);
    }
}
