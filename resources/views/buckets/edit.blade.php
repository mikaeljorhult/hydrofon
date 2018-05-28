@extends('layouts.app')

@section('content')
    <section class="container">
        <h1>Edit bucket</h1>

        <section>
            {!! Form::model($bucket, ['route' => ['buckets.update', $bucket->id], 'method' => 'PUT' ]) !!}
                @include('buckets._form', ['submitButtonText' => 'Update'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
