@extends('layouts.app')

@section('content')
    @include('partials.object-list')

    <main class="main-content">
        @include('partials/topbar')

        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <section class="container">
            &nbsp;
        </section>
    </main>
@endsection
