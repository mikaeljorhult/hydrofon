<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionDestroyRequest;
use App\Http\Requests\SubscriptionStoreRequest;
use App\Scopes\GroupPolicyScope;
use App\Models\Subscription;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param string $uuid
     *
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $subscription = Subscription::where('uuid', $uuid)->with([
            'subscribable' => function ($query) {
                $query->withoutGlobalScope(GroupPolicyScope::class)->get();
            },
        ])->firstOrFail();

        return response()->streamDownload(function () use ($subscription) {
            echo $subscription->toCalendar();
        }, Str::slug($subscription->subscribable->name).'.ics', ['Content-Type' => 'text/calendar; charset=UTF-8']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\SubscriptionStoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(SubscriptionStoreRequest $request)
    {
        Subscription::firstOrCreate([
            'subscribable_type' => 'App\\'.Str::ucfirst($request->get('subscribable_type')),
            'subscribable_id'   => $request->get('subscribable_id'),
        ]);

        flash('Subscription was created');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Subscription                             $subscription
     * @param \App\Http\Requests\SubscriptionDestroyRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription, SubscriptionDestroyRequest $request)
    {
        $subscription->delete();

        flash('Subscription was deleted');

        return redirect()->back();
    }
}
