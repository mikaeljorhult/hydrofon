@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading :title="$bucket->name" />
    </section>
@endsection
