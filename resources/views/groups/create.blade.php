@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading :title="'Create group'" />

        <section>
            {!! Form::open(['route' => 'groups.store']) !!}
                @include('groups._form', ['submitButtonText' => 'Create'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
