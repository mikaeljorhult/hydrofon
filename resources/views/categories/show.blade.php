@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading :title="$category->name" />
    </section>
@endsection
