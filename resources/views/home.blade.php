@extends('layouts.app')

@section('content')
    @include('partials.object-list')

    <main class="main-content">
        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <p>MAIN</p>
        <button id="toggle-logged-in">Toggle</button>
    </main>
@endsection
