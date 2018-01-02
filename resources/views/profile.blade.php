@extends('layouts.app')

@section('content')
    <main class="main-content">
        <header>
            <h1>{{ $user->name }}</h1>
        </header>
    </main>
@endsection
