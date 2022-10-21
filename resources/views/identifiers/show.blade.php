@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading title="Identifier" />

        <section>
            <div class="flex">
                <div>
                    <div class="p-1 bg-gradient-to-br from-red-600 to-black">
                        <div class="p-4 bg-white">
                            <div>{{ $identifier->QrCode() }}</div>
                        </div>
                    </div>

                    <div class="mt-2 pb-2 text-center">
                        {{ $resource->name }}
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection
