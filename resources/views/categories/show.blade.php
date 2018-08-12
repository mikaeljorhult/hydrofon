@extends('layouts.app')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => $category->name])
        @endcomponent
    </section>
@endsection
