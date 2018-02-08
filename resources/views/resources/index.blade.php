@extends('layouts.app')

@section('content')
    <main class="main-content">
        <section class="container">
            <h1>
                <a href="{{ route('resources.index') }}">Resources</a>
            </h1>

            <a href="{{ route('resources.create') }}">New resource</a>

            <table class="table" cellspacing="0">
                <thead>
                    <th>#</th>
                    <th>Name</th>
                    <th>Description</th>
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

            {{ $resources->links() }}
        </section>
    </main>
@endsection
