<?php

namespace App\Http\Controllers;

use App\Enums\ApprovalSetting;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\User;
use App\Settings\General;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\QueryBuilder\QueryBuilder;

class ApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Bail if approvals are not set up.
        if (app(General::class)->require_approval === ApprovalSetting::NONE->value) {
            abort(403);
        }

        $this->authorize('approveAny', Booking::class);

        $items = QueryBuilder::for(Booking::class)
            ->select('bookings.*')
            ->with(['resource', 'user'])
            ->pending()
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

        $filterResources = Resource::orderBy('name')->pluck('name', 'id');

        return view('approvals.index')->with(compact([
            'items',
            'filterResources',
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $booking = Booking::findOrFail($request->input('booking_id'));

        $this->authorize('approve', $booking);

        $booking->approve();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking): RedirectResponse
    {
        $this->authorize('approve', $booking);

        $booking->revoke();

        return redirect()->back();
    }
}
