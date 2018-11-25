@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <section class="container">
        <h1>{{ $user->name }}</h1>
    </section>
@endsection
