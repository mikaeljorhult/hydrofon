@extends('layouts.app')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Edit user'])
        @endcomponent

        <section>
            {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'PUT' ]) !!}
                @include('users._form', ['submitButtonText' => 'Update'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
