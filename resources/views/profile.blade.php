@extends('layouts.app')

@section('content')
    <main class="main-content">
        <section class="container">
            <h1>{{ $user->name }}</h1>
        </section>
    </main>
@endsection
