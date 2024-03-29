@extends('layouts.guest')

@section('content')
    <div class="relative w-full h-full flex flex-col items-center justify-center">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="w-full sm:w-1/2 md:w-1/3 p-4">
            <h1 class="mb-4">Password Reset</h1>

            <form method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}

                <div class="mb-6{{ $errors->has('email') ? ' has-error' : '' }}">
                    <x-forms.label for="email" class="sr-only">E-mail</x-forms.label>
                    <x-forms.input
                        type="email"
                        name="email"
                        id="email"
                        placeholder="E-mail"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        :hasErrors="$errors->has('email')"
                    />

                    @if ($errors->has('email'))
                        <div class="help-block">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>

                <div>
                    <x-forms.button>
                        Send reset link
                    </x-forms.button>
                </div>
            </form>
        </div>

        <a
            class="absolute top-4 left-4"
            href="{{ route('home') }}"
        >&leftarrow; {{ config('app.name', 'Hydrofon') }}</a>
    </div>
@endsection
