@extends('layouts.app')

@section('title', 'Calendar')

@section('sidebar')
    @include('partials.resource-tree')
@endsection

@section('content')
    <section class="container">
        @livewire('segel', ['resources' => $resources, 'date' => $date])
    </section>
@endsection
