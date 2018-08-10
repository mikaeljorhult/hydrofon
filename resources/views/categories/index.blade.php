@extends('layouts.app')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Categories', 'url' => route('categories.index')])
            <a href="{{ route('categories.create') }}" class="btn btn-primary btn-pill mr-2">New category</a>

            {!! Form::open(['route' => 'categories.index', 'method' => 'GET']) !!}
                {!! Form::search('filter[name]', null, ['placeholder' => 'Filter']) !!}
                {!! Form::submit('Search', ['class' => 'btn btn-primary screen-reader']) !!}
            {!! Form::close() !!}
        @endcomponent

        <table class="table" cellspacing="0">
            <thead>
                <th class="table-column-check">#</th>
                <th><a href="{{ route('categories.index', ['sort' => (request('sort') === 'name' || request()->has('sort') === false ? '-' : '') . 'name'] + request()->except('page')) }}">Name</a></th>
                <th><a href="{{ route('categories.index', ['sort' => (request('sort') === 'parent.name' ? '-' : '') . 'parent.name'] + request()->except('page')) }}">Parent</a></th>
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
                            {{ optional($category->parent)->name }}
                        </td>
                        <td data-title="&nbsp;" class="table-actions">
                            {!! Form::model($category, ['route' => ['categories.destroy', $category->id], 'method' => 'DELETE' ]) !!}
                            <button type="submit" title="Delete">
                                @svg('trash', 'w-5')
                            </button>
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

        {{ $categories->appends(['filter' => request()->get('filter'), 'sort' => request()->get('sort')])->links() }}
    </section>
@endsection
