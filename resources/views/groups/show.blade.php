@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading :title="$group->name" />
    </section>
@endsection
