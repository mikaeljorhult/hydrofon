@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading :title="$resource->name" />

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <dl>
                <div>
                    <dt>Name</dt>
                    <dd>{{ $resource->name }}</dd>
                </div>

                <div>
                    <dt>Description</dt>
                    <dd>{{ $resource->description }}</dd>
                </div>
            </dl>

            <x-timeline :activities="$resource->activities" />
        </div>
    </section>
@endsection
