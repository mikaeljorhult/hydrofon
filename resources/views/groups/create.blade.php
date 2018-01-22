@extends('layouts.app')

@section('content')
    <main class="main-content">
        <section class="container">
            <h1>Create group</h1>

            <section>
                {!! Form::open(['route' => 'groups.store']) !!}
                    @include('groups._form', ['submitButtonText' => 'Create'])
                {!! Form::close() !!}
            </section>
        </section>
    </main>
@endsection
