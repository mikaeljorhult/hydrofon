@extends('layouts.app')

@section('content')
    <main class="main-content">
        <table class="table">
            <thead>
                <th>#</th>
                <th>Name</th>
                <th>Parent</th>
                <th>&nbsp;</th>
            </thead>

            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td data-title="&nbsp;"></td>
                        <td data-title="Name">
                            <a href="{{ route('categories.edit', $category) }}">{{ $category->name }}</a>
                        </td>
                        <td data-title="Parent">
                            {{ $category->parent_id }}
                        </td>
                        <td data-title="&nbsp;">
                            {!! Form::model($category, ['route' => ['categories.destroy', $category->id], 'method' => 'DELETE' ]) !!}
                                {!! Form::submit('Delete') !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No categories was found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </main>
@endsection
