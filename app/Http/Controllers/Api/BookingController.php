<?php

namespace Hydrofon\Http\Controllers\Api;

use Hydrofon\Booking;
use Hydrofon\Http\Resources\Booking as BookingResource;
use Hydrofon\Http\Resources\BookingCollection;
use Illuminate\Http\Request;
use Hydrofon\Http\Controllers\Controller;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

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
            ->allowedFilters([
                'start_time',
                'end_time',
                'resource_id',
                Filter::scope('between'),
            ])
            ->defaultSort('start_time')
            ->allowedSorts(['start_time', 'end_time'])
            ->paginate(15);

        return new BookingCollection($bookings);
    }
}
