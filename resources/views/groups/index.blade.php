@extends('layouts.app')

@section('content')
    <main class="main-content">
        <section class="container">
            <header class="main-header">
                <h1>
                    <a href="{{ route('groups.index') }}">Groups</a>
                </h1>

                <aside>
                    <a href="{{ route('groups.create') }}">New group</a>

                    {!! Form::open(['route' => 'groups.index', 'method' => 'GET']) !!}
                        {!! Form::search('filter', null, ['placeholder' => 'Filter']) !!}
                        {!! Form::submit('Search', ['class' => 'btn btn-primary screen-reader']) !!}
                    {!! Form::close() !!}
                </aside>
            </header>

            <table class="table" cellspacing="0">
                <thead>
                    <th class="table-column-check">#</th>
                    <th><a href="{{ route('groups.index', ['order' => 'name'] + request()->except('page')) }}">Name</a></th>
                    <th>&nbsp;</th>
                </thead>

                <tbody>
                    @forelse($groups as $group)
                        <tr>
                            <td data-title="&nbsp;"></td>
                            <td data-title="Name">
                                <a href="{{ route('groups.edit', $group) }}">{{ $group->name }}</a>
                            </td>
                            <td data-title="&nbsp;" class="table-actions">
                                {!! Form::model($group, ['route' => ['groups.destroy', $group->id], 'method' => 'DELETE' ]) !!}
                                <button type="submit" title="Delete">
                                    @svg('delete')
                                </button>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No groups was found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $groups->appends(['filter' => request()->get('filter'), 'order' => request()->get('order')])->links() }}
        </section>
    </main>
@endsection
