@extends('layouts.app')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => $bucket->name])
        @endcomponent
    </section>
@endsection
