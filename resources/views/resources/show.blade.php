@extends('layouts.app')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => $resource->name])
        @endcomponent
    </section>
@endsection
