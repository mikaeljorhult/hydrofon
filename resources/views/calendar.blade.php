@extends('layouts.app')

@section('content')
    @include('partials.object-list')

    <main class="main-content">
        <p>{{ $date }}</p>

        <section class="segel">
            <div class="segel-container">
                <ul class="segel-objects">
                    @foreach($objects as $object)
                        <li class="segel-object">{{ $object->name }}</li>
                    @endforeach
                </ul>
            </div>
        </section>
    </main>
@endsection
