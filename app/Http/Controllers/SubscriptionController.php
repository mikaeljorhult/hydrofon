<?php

namespace Hydrofon\Http\Controllers;

use Hydrofon\Http\Requests\SubscriptionDestroyRequest;
use Hydrofon\Http\Requests\SubscriptionStoreRequest;
use Hydrofon\Subscription;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Hydrofon\Http\Requests\SubscriptionStoreRequest  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(SubscriptionStoreRequest $request)
    {
        Subscription::firstOrCreate([
            'subscribable_type' => '\\Hydrofon\\'.Str::ucfirst($request->get('subscribable_type')),
            'subscribable_id'   => $request->get('subscribable_id'),
        ]);

        flash('Subscription was created');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Hydrofon\Subscription  $subscription
     * @param  \Hydrofon\Http\Requests\SubscriptionDestroyRequest  $request
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
