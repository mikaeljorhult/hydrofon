<?php

namespace Hydrofon\Http\Controllers\Api;

use Hydrofon\Booking;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Hydrofon\Http\Controllers\Controller;
use Hydrofon\Http\Resources\BookingCollection;
use Hydrofon\Http\Requests\BookingStoreRequest;
use Hydrofon\Http\Requests\BookingUpdateRequest;
use Hydrofon\Http\Requests\BookingDestroyRequest;
use Hydrofon\Http\Resources\Booking as BookingResource;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Hydrofon\Http\Resources\BookingCollection
     */
    public function index()
    {
        $bookings = QueryBuilder::for(Booking::class)
            ->allowedIncludes([
                'user',
            ])
            ->allowedFilters([
                'start_time',
                'end_time',
                'resource_id',
                AllowedFilter::scope('between'),
            ])
            ->allowedSorts([
                'start_time',
                'end_time',
            ])
            ->defaultSort('start_time')
            ->paginate(15);

        return new BookingCollection($bookings);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Hydrofon\Http\Requests\BookingStoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BookingStoreRequest $request)
    {
        BookingResource::withoutWrapping();
        $currentUser = auth()->user();

        $booking = Booking::create(array_merge($request->all(), [
            'user_id'       => $currentUser->isAdmin() && $request->input('user_id') ? $request->input('user_id') : $currentUser->id,
            'created_by_id' => $currentUser->id,
        ]));

        return (new BookingResource($booking))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param \Hydrofon\Booking $booking
     *
     * @return \Hydrofon\Http\Resources\Booking
     */
    public function show(Booking $booking)
    {
        BookingResource::withoutWrapping();

        return new BookingResource($booking);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Hydrofon\Http\Requests\BookingUpdateRequest $request
     * @param \Hydrofon\Booking                            $booking
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BookingUpdateRequest $request, Booking $booking)
    {
        BookingResource::withoutWrapping();
        $currentUser = auth()->user();

        $booking->update(array_merge($request->all(), [
            'user_id' => $currentUser->isAdmin() && $request->input('user_id') ? $request->input('user_id') : $booking->user_id,
        ]));

        return (new BookingResource($booking))
            ->response()
            ->setStatusCode(202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Hydrofon\Booking                             $booking
     * @param \Hydrofon\Http\Requests\BookingDestroyRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Booking $booking, BookingDestroyRequest $request)
    {
        $booking->delete();

        return response()->json(null, 204);
    }
}
