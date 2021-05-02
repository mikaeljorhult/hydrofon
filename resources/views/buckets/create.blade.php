@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading :title="'Create bucket'" />

        <section>
            {!! Form::open(['route' => 'buckets.store']) !!}
                @include('buckets._form', ['submitButtonText' => 'Create'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
