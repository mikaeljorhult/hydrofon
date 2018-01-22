@extends('layouts.app')

@section('content')
    <main class="main-content">
        <section class="container">
            <h1>
                <a href="{{ route('groups.index') }}">Groups</a>
            </h1>

            <a href="{{ route('groups.create') }}">New group</a>

            <table class="table" cellspacing="0">
                <thead>
                    <th>#</th>
                    <th>Name</th>
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
                            <td colspan="4">No groups was found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $groups->links() }}
        </section>
    </main>
@endsection
