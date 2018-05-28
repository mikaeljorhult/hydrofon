@extends('layouts.app')

@section('content')
    <section class="container">
        <h1>Edit group</h1>

        <section>
            {!! Form::model($group, ['route' => ['groups.update', $group->id], 'method' => 'PUT' ]) !!}
                @include('groups._form', ['submitButtonText' => 'Update'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
