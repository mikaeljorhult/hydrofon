@extends('layouts.app')

@section('content')
    @include('partials.object-list')

    <main class="main-content">
        @include('partials/topbar')
        @include('flash::message')

        <section class="container">
            &nbsp;
        </section>
    </main>
@endsection
