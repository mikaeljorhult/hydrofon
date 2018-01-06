@extends('layouts.app')

@section('content')
    <main class="main-content">
        <section class="container">
            <h1>Objects</h1>

            <a href="{{ route('objects.create') }}">New object</a>

            <table class="table">
                <thead>
                    <th>#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>&nbsp;</th>
                </thead>

                <tbody>
                    @forelse($objects as $object)
                        <tr>
                            <td data-title="&nbsp;"></td>
                            <td data-title="Name">
                                <a href="{{ route('objects.edit', $object) }}">{{ $object->name }}</a>
                            </td>
                            <td data-title="Description">
                                {{ $object->description }}
                            </td>
                            <td data-title="&nbsp;">
                                {!! Form::model($object, ['route' => ['objects.destroy', $object->id], 'method' => 'DELETE' ]) !!}
                                    {!! Form::submit('Delete') !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No objects was found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </main>
@endsection
