@extends('layouts.app')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => $group->name])
        @endcomponent
    </section>
@endsection
