@extends('layouts.app')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Edit group'])
        @endcomponent

        <section>
            {!! Form::model($group, ['route' => ['groups.update', $group->id], 'method' => 'PUT' ]) !!}
                @include('groups._form', ['submitButtonText' => 'Update'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
