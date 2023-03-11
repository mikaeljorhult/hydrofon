<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingDestroyRequest;
use App\Http\Requests\BookingStoreRequest;
use App\Http\Requests\BookingUpdateRequest;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\QueryBuilder\QueryBuilder;

class BookingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->except(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $items = QueryBuilder::for(Booking::class)
                             ->select('bookings.*')
                             ->with(['resource.buckets', 'user'])
                             ->addSelect([
                                 'user_name' => User::whereColumn('user_id', 'users.id')
                                                    ->select('name')
                                                    ->take(1),
                             ])
                             ->addSelect([
                                 'resource_name' => Resource::whereColumn('resource_id', 'resources.id')
                                                            ->select('name')
                                                            ->take(1),
                             ])
                             ->allowedFilters([
                                 'resource_id',
                                 'user_id',
                                 'start_time',
                                 'end_time',
                                 'state',
                             ])
                             ->defaultSort('start_time')
                             ->allowedSorts(['resource_name', 'user_name', 'start_time', 'end_time', 'state'])
                             ->paginate(15);

        $filterResources = Resource::orderBy('name')->pluck('name', 'id');
        $filterUsers = User::orderBy('name')->pluck('name', 'id');

        $filterState = config('hydrofon.require_approval') !== 'none'
            ? [
                'pending' => 'Pending',
                'approved' => 'Approved',
                'rejected' => 'Rejected',
                'checkedout' => 'Checked out',
                'checkedin' => 'Checked in',
            ]
            : [
                'approved' => 'Approved',
                'checkedout' => 'Checked out',
                'checkedin' => 'Checked in',
            ];

        return view('bookings.index')->with(compact([
            'items',
            'filterResources',
            'filterUsers',
            'filterState',
        ]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $resourceOptions = Resource::orderBy('name')->pluck('name', 'id');
        $userOptions = User::orderBy('name')->pluck('name', 'id');

        return view('bookings.create')->with(compact([
            'resourceOptions',
            'userOptions',
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookingStoreRequest $request): RedirectResponse
    {
        $currentUser = auth()->user();

        Booking::create(array_merge($request->validated(), [
            'user_id' => $currentUser->isAdmin() && $request->input('user_id') ? $request->input('user_id') : $currentUser->id,
        ]));

        laraflash()
            ->message()
            ->title('Booking was created')
            ->content('Booking was created successfully.')
            ->success();

        return redirect()->route('bookings.index');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        $booking->load([
            'resource',
            'user',
            'activities' => function ($query) {
                $query->with('causer:id,name')
                      ->when(config('hydrofon.require_approval') === 'none', function ($query) {
                          // Ignore logs for approval states if approval isn't use.
                          $query->whereNotIn('event', ['pending', 'approved', 'autoapproved', 'rejected']);
                      })
                      ->oldest();
            },
        ]);

        // Remove save where initial values are set.
        $activities = $booking->activities->filter(function ($activity) {
            if ($activity->event !== 'updated') {
                return true;
            }

            return collect($activity->changes()['old'])
                ->values()
                ->whereNotNull()
                ->isNotEmpty();
        });

        return view('bookings.show')->with(compact([
            'booking',
            'activities',
        ]));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking): View
    {
        $resourceOptions = Resource::orderBy('name')->pluck('name', 'id');
        $userOptions = User::orderBy('name')->pluck('name', 'id');

        return view('bookings.edit')->with(compact([
            'booking',
            'resourceOptions',
            'userOptions',
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookingUpdateRequest $request, Booking $booking): RedirectResponse
    {
        $currentUser = auth()->user();

        $booking->update(array_merge($request->validated(), [
            'user_id' => $currentUser->isAdmin() && $request->input('user_id') ? $request->input('user_id') : $booking->user_id,
        ]));

        laraflash()
            ->message()
            ->title('Booking was updated')
            ->content('Booking was updated successfully.')
            ->success();

        return redirect()->route('bookings.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking, BookingDestroyRequest $request): RedirectResponse
    {
        $booking->delete();

        laraflash()
            ->message()
            ->title('Booking was deleted')
            ->content('Booking was deleted successfully.')
            ->success();

        return redirect()->route('bookings.index');
    }
}
