@extends('layouts.guest')

@section('content')
    <div class="w-full h-full flex flex-col items-center justify-center">
        <div class="w-full sm:w-1/2 md:w-1/3">
            <h1 class="mb-4">Log in</h1>

            <form method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

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

                <div class="mb-6 flex items-center">
                    <x-forms.checkbox name="remember" id="remember" :checked="old('remember')" />

                    <x-forms.label for="remember" class="ml-1">
                        Remember me
                    </x-forms.label>
                </div>

                <div class="flex items-center justify-between">
                    <x-forms.button>
                        Log in
                    </x-forms.button>

                    <x-forms.button-secondary
                        type="link"
                        :href="route('password.request')"
                    >Forgot password?</x-forms.button-secondary>
                </div>
            </form>
        </div>
    </div>
@endsection
