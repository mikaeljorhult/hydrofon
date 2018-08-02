@extends('layouts.app')

@section('content')
    <section class="container">
        <header class="main-header">
            <h1>
                <a href="{{ route('buckets.index') }}">Buckets</a>
            </h1>

            <aside>
                <a href="{{ route('buckets.create') }}">New bucket</a>

                {!! Form::open(['route' => 'buckets.index', 'method' => 'GET']) !!}
                    {!! Form::search('filter[name]', null, ['placeholder' => 'Filter']) !!}
                    {!! Form::submit('Search', ['class' => 'btn btn-primary screen-reader']) !!}
                {!! Form::close() !!}
            </aside>
        </header>

        <table class="table" cellspacing="0">
            <thead>
                <th class="table-column-check">#</th>
                <th><a href="{{ route('buckets.index', ['sort' => (request('sort') === 'name' || request()->has('sort') === false ? '-' : '') . 'name'] + request()->except('page')) }}">Name</a></th>
                <th>&nbsp;</th>
            </thead>

            <tbody>
                @forelse($buckets as $bucket)
                    <tr>
                        <td data-title="&nbsp;"></td>
                        <td data-title="Name">
                            <a href="{{ route('buckets.edit', $bucket) }}">{{ $bucket->name }}</a>
                        </td>
                        <td data-title="&nbsp;" class="table-actions">
                            {!! Form::model($bucket, ['route' => ['buckets.destroy', $bucket->id], 'method' => 'DELETE' ]) !!}
                            <button type="submit" title="Delete">
                                @svg('delete')
                            </button>
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No buckets was found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $buckets->appends(['filter' => request()->get('filter'), 'sort' => request()->get('sort')])->links() }}
    </section>
@endsection
