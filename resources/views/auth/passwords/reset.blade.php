@extends('layouts.guest')

@section('content')
    <div class="relative w-full h-full flex flex-col items-center justify-center">
        <div class="w-full sm:w-1/2 md:w-1/3 p-4">
            <h1 class="mb-4">Password Reset</h1>

            <form method="POST" action="{{ route('password.request') }}">
                {{ csrf_field() }}

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-4{{ $errors->has('email') ? ' has-error' : '' }}">
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

                <div class="mb-4{{ $errors->has('password') ? ' has-error' : '' }}">
                    <x-forms.label for="password" class="sr-only">Password</x-forms.label>
                    <x-forms.input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Password"
                        required
                        :hasErrors="$errors->has('password')"
                    />

                    @if ($errors->has('password'))
                        <div class="help-block">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>

                <div class="mb-6{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <x-forms.label for="password_confirmation" class="sr-only">Confirm password</x-forms.label>
                    <x-forms.input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        placeholder="Confirm password"
                        required
                        :hasErrors="$errors->has('password_confirmation')"
                    />

                    @if ($errors->has('password_confirmation'))
                        <div class="help-block">
                            {{ $errors->first('password_confirmation') }}
                        </div>
                    @endif
                </div>

                <div>
                    <x-forms.button>
                        Reset password
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
