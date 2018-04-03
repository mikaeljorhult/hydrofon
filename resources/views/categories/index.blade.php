@extends('layouts.app')

@section('content')
    <main class="main-content">
        <section class="container">
            <header class="main-header">
                <h1>
                    <a href="{{ route('categories.index') }}">Categories</a>
                </h1>

                <aside>
                    <a href="{{ route('categories.create') }}">New category</a>

                    {!! Form::open(['route' => 'categories.index', 'method' => 'GET']) !!}
                        {!! Form::search('filter', null, ['placeholder' => 'Filter']) !!}
                        {!! Form::submit('Search', ['class' => 'btn btn-primary screen-reader']) !!}
                    {!! Form::close() !!}
                </aside>
            </header>

            <table class="table" cellspacing="0">
                <thead>
                    <th class="table-column-check">#</th>
                    <th><a href="{{ route('categories.index', ['order' => 'name'] + request()->except('page')) }}">Name</a></th>
                    <th><a href="{{ route('categories.index', ['order' => 'parent'] + request()->except('page')) }}">Parent</a></th>
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
                                    @svg('delete')
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

            {{ $categories->appends(['filter' => request()->get('filter'), 'order' => request()->get('order')])->links() }}
        </section>
    </main>
@endsection
