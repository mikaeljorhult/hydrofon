@extends('layouts.app')

@section('content')
    <main class="main-content">
        <section class="container">
            <header class="main-header">
                <h1>
                    <a href="{{ route('resources.index') }}">Resources</a>
                </h1>

                <aside>
                    <a href="{{ route('resources.create') }}">New resource</a>

                    {!! Form::open(['route' => 'resources.index', 'method' => 'GET']) !!}
                        {!! Form::search('filter', null, ['placeholder' => 'Filter']) !!}
                        {!! Form::submit('Search', ['class' => 'btn btn-primary screen-reader']) !!}
                    {!! Form::close() !!}
                </aside>
            </header>

            <table class="table" cellspacing="0">
                <thead>
                    <th class="table-column-check">#</th>
                    <th><a href="{{ route('resources.index', ['order' => 'name'] + request()->except('page')) }}">Name</a></th>
                    <th><a href="{{ route('resources.index', ['order' => 'description'] + request()->except('page')) }}">Description</a></th>
                    <th>&nbsp;</th>
                </thead>

                <tbody>
                    @forelse($resources as $resource)
                        <tr>
                            <td data-title="&nbsp;"></td>
                            <td data-title="Name">
                                <a href="{{ route('resources.edit', $resource) }}">{{ $resource->name }}</a>
                            </td>
                            <td data-title="Description">
                                {{ $resource->description }}
                            </td>
                            <td data-title="&nbsp;" class="table-actions">
                                {!! Form::model($resource, ['route' => ['resources.destroy', $resource->id], 'method' => 'DELETE' ]) !!}
                                <button type="submit" title="Delete">
                                    @svg('delete')
                                </button>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No resources was found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $resources->appends(['filter' => request()->get('filter'), 'order' => request()->get('order')])->links() }}
        </section>
    </main>
@endsection
