@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <section class="container space-y-8">
        <x-heading :title="$user->name" :url="route('profile')" />

        @if($latest->isNotEmpty() || $upcoming->isNotEmpty() || $overdue->isNotEmpty())
            <div>
                <h2>Bookings</h2>

                <div class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                    @if($latest->isNotEmpty())
                        <div class="p-4 pt-3 bg-gray-100">
                            <h3 class="mt-0 mb-2">Latest</h3>
                            @foreach($latest as $booking)
                                <ul class="mb-3">
                                    <li>{{ $booking->start_time->format('Y-m-d H:i') }}: {{ $booking->resource->name }}</li>
                                </ul>
                            @endforeach

                            <div class="text-right">
                                <a href="{{ route('profile.bookings') }}">See all bookings</a>
                            </div>
                        </div>
                    @endif

                    @if($upcoming->isNotEmpty())
                        <div class="p-4 pt-3 bg-gray-100">
                            <h3 class="mt-0 mb-2">Upcoming</h3>
                            @foreach($upcoming as $day)
                                <h3 class="mt-0 mb-1 text-sm font-normal">{{ $day->first()->start_time->format('Y-m-d') }}</h3>
                                <ul class="mb-3">
                                    @foreach($day as $booking)
                                        <li>{{ $booking->resource->name }}</li>
                                    @endforeach
                                </ul>
                            @endforeach
                        </div>
                    @endif

                    @if($overdue->isNotEmpty())
                        <div class="p-4 pt-3 bg-gray-100">
                            <h3 class="mt-0 mb-2">Overdue</h3>
                            <ul class="mb-3">
                                @foreach($overdue as $booking)
                                    <li>
                                        {{ $booking->resource->name }}
                                        <span class="text-sm">
                                            ({{ $booking->end_time->diffForHumans() }})
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <div>
            <h2>Calendar Subscription</h2>

            <div class="max-w-xl">
                @if($user->subscription)
                    <p class="mb-2">
                        Copy the iCal feed URL below to the calendar application of choice to subscribe to upcoming bookings.
                    </p>

                    <p class="mb-4">
                        <x-forms.input name="feed" value="{{ route('feed', [$user->subscription->uuid]) }}" readonly />
                    </p>

                    <form action="{{ route('subscriptions.destroy', [$user->subscription->id]) }}" method="post">
                        @method('delete')
                        @csrf

                        <x-forms.button>
                            End subscription
                        </x-forms.button>
                    </form>
                @else
                    <p class="mb-4">
                        You can setup an iCal feed to be able to subscribe and see your upcoming bookings from within your
                        calendar application of choice.
                    </p>

                    <form action="{{ route('subscriptions.store') }}" method="post">
                        @csrf

                        <input type="hidden" name="subscribable_type" value="user">
                        <input type="hidden" name="subscribable_id" value="{{ $user->id }}">

                        <x-forms.button>
                            Create subscription
                        </x-forms.button>
                    </form>
                @endif
            </div>
        </div>

        <div>
            <h2>Personal Data</h2>

            <div class="max-w-xl">
                <p class="mb-4">
                    You can request an export of all the data attached to your account. A file containing JSON will be
                    generated and downloaded.
                </p>

                <form action="{{ route('datarequests.store') }}" method="post">
                    @csrf
                    <x-forms.button>
                        Request Data
                    </x-forms.button>
                </form>
            </div>
        </div>
    </section>
@endsection
