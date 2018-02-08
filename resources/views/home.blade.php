@extends('layouts.app')

@section('content')
    @include('partials.resource-list')

    <main class="main-content">
        @include('partials/topbar')
        @include('flash::message')

        <section class="container">
            &nbsp;
        </section>
    </main>
@endsection
