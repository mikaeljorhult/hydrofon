@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading :title="'Edit group'" />

        <section>
            {!! Form::model($group, ['route' => ['groups.update', $group->id], 'method' => 'PUT' ]) !!}
                @include('groups._form', ['submitButtonText' => 'Update'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
