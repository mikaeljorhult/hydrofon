@extends('layouts.app')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Edit category'])
        @endcomponent

        <section>
            {!! Form::model($category, ['route' => ['categories.update', $category->id], 'method' => 'PUT' ]) !!}
                @include('categories._form', ['submitButtonText' => 'Update'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
