@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
    <section class="container mx-auto md:max-w-2xl">
        <x-heading title="Notifications" :url="route('notifications')" />

        <div>
            @if($notifications->isNotEmpty())
                <ul role="list" class="divide-y divide-gray-300">
                    @foreach($notifications as $notification)
                        <li class="py-4 {{ $loop->first ? 'pt-0' : '' }}">
                            <div class="flex space-x-3">
                                <div class="text-gray-600">
                                    <x-dynamic-component
                                        :component="'heroicon-s-' . $notification->data['icon']"
                                        class="h-6 w-6 rounded-full"
                                    />
                                </div>

                                <div class="flex-1 space-y-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-medium">{{ $notification->data['title'] }}</h3>
                                        <p class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>

                                    <p class="text-sm text-gray-500">{{ $notification->data['body'] }}</p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-sm">You have no new notifications.</div>
            @endif
        </div>
    </section>
@endsection
