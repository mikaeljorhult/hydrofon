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
        @endif
    </section>
@endsection

