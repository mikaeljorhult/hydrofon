@extends('layouts.app')

@section('content')
    @include('partials.object-list')

    <main class="main-content">
        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
    </main>
@endsection
