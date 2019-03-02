<?php

namespace Hydrofon\Http\Controllers;

use Hydrofon\Http\Requests\DeskRequest;
use Hydrofon\User;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

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
        // Only resolve user if a search string is available.
        $user = $search ? $this->resolveUser($search) : null;

        // Get bookings for user or empty collection.
        $bookings = $user
            ? $this->getUserBookings($user)
            : collect();

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

    /**
     * Get bookings of user.
     *
     * @param \Hydrofon\User $user
     *
     * @return mixed
     */
    private function getUserBookings(User $user)
    {
        return QueryBuilder::for($user->bookings()->getQuery())
                           ->with(['checkin', 'checkout', 'resource', 'user'])
                           ->whereDoesntHave('checkin')
                           ->where(function ($query) {
                               $filter = request()->query->get('filter');

                               // Set default time span to +/- 4 days if not in request.
                               if (!isset($filter['between'])) {
                                   $query->between(now()->subDays(4), now()->addDays(4));
                               }
                           })
                           ->join('resources', 'resources.id', '=', 'bookings.resource_id')
                           ->join('users', 'users.id', '=', 'bookings.user_id')
                           ->allowedFilters(Filter::scope('between'))
                           ->defaultSort('start_time')
                           ->allowedSorts(['resources.name', 'start_time', 'end_time'])
                           ->paginate(15);
    }
}
