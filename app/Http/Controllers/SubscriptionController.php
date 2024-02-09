<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionDestroyRequest;
use App\Http\Requests\SubscriptionStoreRequest;
use App\Models\Subscription;
use App\Scopes\GroupPolicyScope;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscriptionController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(string $uuid): StreamedResponse
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
     */
    public function store(SubscriptionStoreRequest $request): RedirectResponse
    {
        Subscription::firstOrCreate([
            'subscribable_type' => 'App\\Models\\'.Str::ucfirst($request->get('subscribable_type')),
            'subscribable_id' => $request->get('subscribable_id'),
        ]);

        flash(json_encode([
            'title' => 'Subscription was added',
            'message' => 'Subscription was added successfully.',
        ]), 'success');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription, SubscriptionDestroyRequest $request): RedirectResponse
    {
        $subscription->delete();

        flash(json_encode([
            'title' => 'Subscription was removed',
            'message' => 'Subscription was removed successfully.',
        ]), 'success');

        return redirect()->back();
    }
}
