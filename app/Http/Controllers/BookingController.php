<?php

namespace Hydrofon\Http\Controllers;

use Hydrofon\Booking;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\QueryBuilder;
use Hydrofon\Http\Requests\BookingStoreRequest;
use Hydrofon\Http\Requests\BookingUpdateRequest;
use Hydrofon\Http\Requests\BookingDestroyRequest;

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
                                ->with(['checkin', 'checkout', 'resource', 'user'])
                                ->join('resources', 'resources.id', '=', 'bookings.resource_id')
                                ->join('users', 'users.id', '=', 'bookings.user_id')
                                ->allowedFilters(['resource_id', 'user_id', 'start_time', 'end_time'])
                                ->defaultSort('start_time')
                                ->allowedSorts(['resources.name', 'users.name', 'start_time', 'end_time'])
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
     * @param \Hydrofon\Http\Requests\BookingStoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(BookingStoreRequest $request)
    {
        $currentUser = auth()->user();

        Booking::create(array_merge($request->all(), [
            'user_id'       => $currentUser->isAdmin() && $request->input('user_id') ? $request->input('user_id') : $currentUser->id,
            'created_by_id' => $currentUser->id,
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
     * @param \Hydrofon\Booking $booking
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        return view('bookings.show')->with('booking', $booking);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Hydrofon\Booking $booking
     *
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
     * @param \Hydrofon\Http\Requests\BookingUpdateRequest $request
     * @param \Hydrofon\Booking                            $booking
     *
     * @return \Illuminate\Http\Response
     */
    public function update(BookingUpdateRequest $request, Booking $booking)
    {
        $currentUser = auth()->user();

        $booking->update(array_merge($request->all(), [
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
     * @param \Hydrofon\Booking                             $booking
     * @param \Hydrofon\Http\Requests\BookingDestroyRequest $request
     *
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
