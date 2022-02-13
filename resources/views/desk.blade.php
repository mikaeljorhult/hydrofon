@extends('layouts.app')

@section('title', 'Desk')

@section('content')
    <section class="container">
        @if($identifiable)
            <x-heading :title="$identifiable->name" />

            <h2>Bookings</h2>
            @livewire('bookings-table', ['items' => $bookings->getCollection()])

            {{ $bookings->appends(['filter' => request()->get('filter'), 'sort' => request()->get('sort')])->links() }}
        @elseif($search)
            <p>No resource or user was found.</p>
        @else
            <section class="mx-auto md:max-w-2xl">
                <x-heading title="Desk" :url="route('desk')" />

                <form action="{{ route('desk') }}" method="post">
                    @csrf

                    <x-forms.label for="desk-search" class="sr-only">
                        Search for resource or user
                    </x-forms.label>

                    <div class="flex gap-x-2">
                        <x-forms.input
                            type="search"
                            id="desk-search"
                            name="search"
                            placeholder="Search for resource or user"
                        />

                        <x-forms.button>
                            Search
                        </x-forms.button>
                    </div>
                </form>
            </section>
        @endif
    </section>
@endsection

