<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProfileBookingsController extends Controller
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
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        $user = auth()->user();
        $bookings = QueryBuilder::for(auth()->user()->bookings()->getQuery())
                                ->select('bookings.*')
                                ->with(['user'])
                                ->join('resources', 'resources.id', '=', 'bookings.resource_id')
                                ->allowedFilters([
                                    'resource_id',
                                    'start_time',
                                    'end_time',
                                    AllowedFilter::scope('upcoming', 'future'),
                                    AllowedFilter::scope('overdue'),
                                ])
                                ->defaultSort('start_time')
                                ->allowedSorts(['resources.name', 'start_time', 'end_time'])
                                ->paginate(15);

        $filterResources = Resource::orderBy('name')->pluck('name', 'id');

        return view('profile.bookings')->with(compact([
            'user',
            'bookings',
            'filterResources',
        ]));
    }
}
