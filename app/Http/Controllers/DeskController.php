<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\DeskRequest;
use App\Models\Identifier;
use App\Models\User;
use App\States\CheckedIn;
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
     * @param  null|string  $search
     * @return \Illuminate\Http\Response
     */
    public function index(?string $search = null): View
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
     * @param  \App\Http\Requests\DeskRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeskRequest $request): RedirectResponse
    {
        return redirect('/desk/'.$request->input('search'));
    }

    /**
     * Resolve resource or user from search term.
     * Checks against user e-mail address and otherwise against identifiers.
     *
     * @param $search
     * @return mixed
     */
    private function resolveIdentifiable($search)
    {
        $identifiable = User::where('email', $search)->first();

        if (! $identifiable) {
            $identifier = Identifier::with('identifiable')
                                    ->where('value', $search)
                                    ->first();

            $identifiable = $identifier?->identifiable;
        }

        return $identifiable;
    }

    /**
     * Get bookings of identifiable.
     *
     * @param  \App\Resource|\App\Models\User  $identifiable
     * @return mixed
     */
    private function getBookings($identifiable)
    {
        return QueryBuilder::for($identifiable->bookings()->getQuery())
                           ->select('bookings.*')
                           ->with(['resource', 'user'])
                           ->whereNotState('state', CheckedIn::class)
                           ->whereHas('resource', function ($query) {
                               $query->where('is_facility', '=', 0);
                           })
                           ->where(function ($query) {
                               $filter = request()->query->all('filter');

                               if (! isset($filter['between'])) {
                                   $query->between(
                                       now()->subHours(config('hydrofon.desk_inclusion_hours.earlier', 0)),
                                       now()->addHours(config('hydrofon.desk_inclusion_hours.later', 0))
                                   );
                               } else {
                                   $between = explode(',', $filter['between']);
                                   $query->between($between[0], $between[1]);
                               }
                           })
                           ->join('resources', 'resources.id', '=', 'bookings.resource_id')
                           ->join('users', 'users.id', '=', 'bookings.user_id')
                           ->defaultSort('start_time')
                           ->allowedSorts(['resources.name', 'users.name', 'start_time', 'end_time'])
                           ->paginate(15);
    }
}
