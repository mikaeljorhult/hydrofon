<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\QueryBuilder;

class ApprovalController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('list', Approval::class);

        $bookings = QueryBuilder::for(Booking::class)
                                ->select('bookings.*')
                                ->with(['resource', 'user'])
                                ->currentStatus('pending')
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
                                ->allowedFilters(['resource_id', 'user_id', 'start_time', 'end_time'])
                                ->defaultSort('start_time')
                                ->allowedSorts(['resource_name', 'user_name', 'start_time', 'end_time'])
                                ->paginate(15);

        session()->flash('index-referer-url', request()->fullUrl());

        return view('approvals.index')->with('bookings', $bookings);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $booking = Booking::findOrFail($request->input('booking_id'));

        $booking->approve();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Approval  $approval
     * @return \Illuminate\Http\Response
     */
    public function destroy(Approval $approval)
    {
        $this->authorize('delete', $approval);

        // Revoked approval if booking has not yet started.
        if ($approval->booking->start_time->isFuture()) {
            $approval->booking->setStatus('pending', 'Approval revoked');
        }

        $approval->delete();

        return redirect()->back();
    }
}
