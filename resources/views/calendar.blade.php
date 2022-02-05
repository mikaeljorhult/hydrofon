@extends('layouts.app')

@section('title', 'Calendar')

@section('sidebar')
    @include('partials.resource-tree')
@endsection

@section('content')
    <section class="container z-10">
        @livewire('segel', ['resources' => $resources, 'date' => $date])
    </section>
@endsection
