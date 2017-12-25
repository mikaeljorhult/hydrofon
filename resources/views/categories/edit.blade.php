@extends('layouts.app')

@section('content')
    <main class="main-content">
        <h1>Edit category</h1>

        <section>
            {!! Form::model($category, ['route' => ['categories.update', $category->id], 'method' => 'PUT' ]) !!}
                @include('categories._form', ['submitButtonText' => 'Update'])
            {!! Form::close() !!}
        </section>
    </main>
@endsection
