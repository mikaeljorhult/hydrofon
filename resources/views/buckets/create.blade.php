@extends('layouts.app')

@section('content')
    <main class="main-content">
        <section class="container">
            <h1>Create bucket</h1>

            <section>
                {!! Form::open(['route' => 'buckets.store']) !!}
                    @include('buckets._form', ['submitButtonText' => 'Create'])
                {!! Form::close() !!}
            </section>
        </section>
    </main>
@endsection
