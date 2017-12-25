@extends('layouts.app')

@section('content')
    <main class="main-content">
        <h1>Edit user</h1>

        <section>
            {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'PUT' ]) !!}
                @include('users._form', ['submitButtonText' => 'Update'])
            {!! Form::close() !!}
        </section>
    </main>
@endsection
