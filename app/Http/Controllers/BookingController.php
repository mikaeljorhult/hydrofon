<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingDestroyRequest;
use App\Http\Requests\BookingStoreRequest;
use App\Http\Requests\BookingUpdateRequest;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
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
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookings = QueryBuilder::for(Booking::class)
                                ->select('bookings.*')
                                ->with(['checkin', 'checkout', 'resource.buckets', 'user'])
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
                                    AllowedFilter::scope('status', 'currentStatus'),
                                ])
                                ->defaultSort('start_time')
                                ->allowedSorts(['resource_name', 'user_name', 'start_time', 'end_time'])
                                ->paginate(15);

        session()->flash('index-referer-url', request()->fullUrl());

        return view('bookings.index')->with('bookings', $bookings);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (session()->has('index-referer-url')) {
            session()->keep('index-referer-url');
        }

        return view('bookings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\BookingStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookingStoreRequest $request)
    {
        $currentUser = auth()->user();

        Booking::create(array_merge($request->validated(), [
            'user_id' => $currentUser->isAdmin() && $request->input('user_id') ? $request->input('user_id') : $currentUser->id,
        ]));

        flash('Booking was created');

        // Redirect to index if from admin page, otherwise back to referer.
        if (Str::contains($request->headers->get('referer'), '/bookings')) {
            return ($backUrl = session()->get('index-referer-url'))
                ? redirect()->to($backUrl)
                : redirect('/bookings');
        } else {
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        $booking->load([
            'resource',
            'user',
            'activities.causer:id,name',
            'statuses.created_by:id,name',
            'checkin.created_by:id,name',
            'checkout.created_by:id,name',
        ]);

        $events = collect()
            ->concat($booking->activities)
            ->concat($booking->statuses)
            ->when($booking->checkout, function ($collection, $value) {
                return $collection->push($value);
            })
            ->when($booking->checkin, function ($collection, $value) {
                return $collection->push($value);
            })
            ->sortBy('created_at')
            ->map(function ($item) {
                $object = (object) [
                    'type' => strtolower(class_basename($item)),
                    'created_at' => $item->created_at,
                ];

                switch ($object->type) {
                    case 'activity':
                        $object->name = $item->event;
                        $object->created_by = $item->causer;
                        $object->created_by_id = $item->causer_id;
                        break;
                    case 'status':
                        $object->name = $item->name;
                        $object->created_by = $item->created_by;
                        $object->created_by_id = $item->created_by_id;
                        break;
                    case 'checkin':
                    case 'checkout':
                        $object->name = $object->type;
                        $object->created_by = $item->created_by;
                        $object->created_by_id = $item->created_by_id;
                        break;
                }

                return $object;
            });

        return view('bookings.show')->with(compact(['booking', 'events']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        if (session()->has('index-referer-url')) {
            session()->keep('index-referer-url');
        }

        return view('bookings.edit')->with('booking', $booking);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\BookingUpdateRequest  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(BookingUpdateRequest $request, Booking $booking)
    {
        $currentUser = auth()->user();

        $booking->update(array_merge($request->validated(), [
            'user_id' => $currentUser->isAdmin() && $request->input('user_id') ? $request->input('user_id') : $booking->user_id,
        ]));

        flash('Booking was updated');

        // Redirect to index if from admin page, otherwise back to referer.
        if (Str::contains($request->headers->get('referer'), '/bookings')) {
            return ($backUrl = session()->get('index-referer-url'))
                ? redirect()->to($backUrl)
                : redirect('/bookings');
        } else {
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Booking  $booking
     * @param  \App\Http\Requests\BookingDestroyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking, BookingDestroyRequest $request)
    {
        $booking->delete();

        flash('Booking was deleted');

        // Redirect to index if from admin page, otherwise back to referer.
        if (Str::contains($request->headers->get('referer'), '/bookings')) {
            return ($backUrl = session()->get('index-referer-url'))
                ? redirect()->to($backUrl)
                : redirect('/bookings');
        } else {
            return redirect()->back();
        }
    }
}
