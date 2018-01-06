@extends('layouts.app')

@section('content')
    <main class="main-content">
        <section class="container">
            <header>
                <h1>{{ $user->name }}</h1>
            </header>
        </section>
    </main>
@endsection
