@extends('layouts.app')

@section('content')
    <main class="main-content">
        <section class="container">
            <h1>Create identifier</h1>

            <section>
                {!! Form::open(['route' => ['users.identifiers.store', $user]]) !!}
                    @include('identifiers._form', ['submitButtonText' => 'Create'])
                {!! Form::close() !!}
            </section>
        </section>
    </main>
@endsection
