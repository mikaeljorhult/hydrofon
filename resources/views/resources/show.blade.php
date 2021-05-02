@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading :title="$resource->name" />
    </section>
@endsection
