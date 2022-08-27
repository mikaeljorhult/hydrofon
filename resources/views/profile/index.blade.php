@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <section class="container space-y-8">
        <x-heading :title="$user->name" :url="route('profile')" />

        @if($latest->isNotEmpty() || $upcoming->isNotEmpty() || $overdue->isNotEmpty())
            <div>
                <h2>Bookings</h2>

                <dl class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @if($latest->isNotEmpty())
                        <div class="relative bg-white pt-5 px-4 pb-12 sm:pt-6 sm:px-6 shadow rounded-lg overflow-hidden">
                            <dt>
                                <div class="absolute bg-red-600 rounded-md p-3">
                                    <x-heroicon-o-calendar class="h-6 w-6 text-white" />
                                </div>

                                <p class="ml-16 text-sm font-medium text-gray-500 truncate">Latest Bookings</p>
                            </dt>

                            <dd class="ml-16 pb-6 sm:pb-7">
                                <p>
                                    <ul class="mt-2">
                                        @foreach($latest as $booking)
                                            <li class="mb-1">
                                                <span class="text-sm whitespace-nowrap">{{ $booking->start_time->format('Y-m-d H:i') }}:</span>
                                                {{ $booking->resource->name }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </p>

                                <div class="absolute bottom-0 inset-x-0 bg-gray-50 px-4 py-4 sm:px-6">
                                    <div class="text-sm">
                                        <a
                                            href="{{ route('profile.bookings') }}"
                                            class="font-medium text-red-600 hover:text-red-500"
                                        >
                                            View all<span class="sr-only"> Latest bookings</span>
                                        </a>
                                    </div>
                                </div>
                            </dd>
                        </div>
                    @endif

                    @if($upcoming->isNotEmpty())
                        <div class="relative bg-white pt-5 px-4 pb-12 sm:pt-6 sm:px-6 shadow rounded-lg overflow-hidden">
                            <dt>
                                <div class="absolute bg-red-600 rounded-md p-3">
                                    <x-heroicon-o-plus-circle class="h-6 w-6 text-white" />
                                </div>

                                <p class="ml-16 text-sm font-medium text-gray-500 truncate">Upcoming Bookings</p>
                            </dt>

                            <dd class="ml-16 pb-6 sm:pb-7">
                                <p>
                                    @foreach($upcoming as $day)
                                        <h3 class="pt-2 text-sm">{{ $day->first()->start_time->format('Y-m-d') }}</h3>

                                        <ul>
                                            <li>
                                                {{ $booking->resource->name }}
                                            </li>
                                        </ul>
                                    @endforeach
                                </p>

                                <div class="absolute bottom-0 inset-x-0 bg-gray-50 px-4 py-4 sm:px-6">
                                    <div class="text-sm">
                                        <a
                                            href="{{ route('profile.bookings', ['filter[upcoming]=1']) }}"
                                            class="font-medium text-red-600 hover:text-red-500"
                                        >
                                            View all<span class="sr-only"> Upcoming bookings</span>
                                        </a>
                                    </div>
                                </div>
                            </dd>
                        </div>
                    @endif

                    @if($overdue->isNotEmpty())
                        <div class="relative bg-white pt-5 px-4 pb-12 sm:pt-6 sm:px-6 shadow rounded-lg overflow-hidden">
                            <dt>
                                <div class="absolute bg-red-600 rounded-md p-3">
                                    <x-heroicon-o-exclamation-triangle class="h-6 w-6 text-white" />
                                </div>

                                <p class="ml-16 text-sm font-medium text-gray-500 truncate">Overdue Bookings</p>
                            </dt>

                            <dd class="ml-16 pb-6 sm:pb-7">
                                <p>
                                    @foreach($overdue as $booking)
                                        <ul class="pt-2">
                                            <li>
                                                {{ $booking->resource->name }}
                                                <span class="text-sm whitespace-nowrap">({{ $booking->end_time->diffForHumans() }})</span>
                                            </li>
                                        </ul>
                                    @endforeach
                                </p>

                                <div class="absolute bottom-0 inset-x-0 bg-gray-50 px-4 py-4 sm:px-6">
                                    <div class="text-sm">
                                        <a
                                            href="{{ route('profile.bookings', ['filter[overdue]=1']) }}"
                                            class="font-medium text-red-600 hover:text-red-500"
                                        >
                                            View all<span class="sr-only"> Overdue bookings</span>
                                        </a>
                                    </div>
                                </div>
                            </dd>
                        </div>
                    @endif
                </dl>
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
