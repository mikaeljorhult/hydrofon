@extends('layouts.guest')

@section('content')
    <div class="w-full h-full flex flex-col items-center justify-center">
        <div class="w-full sm:w-1/2 md:w-1/3">
            <h1 class="mb-4">Register</h1>

            <form method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}

                <div class="mb-4{{ $errors->has('name') ? ' has-error' : '' }}">
                    <x-forms.label for="name" class="sr-only">Name</x-forms.label>
                    <x-forms.input name="name" id="name" placeholder="Name" value="{{ old('name') }}" required autofocus />

                    @if ($errors->has('name'))
                        <div class="help-block">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>

                <div class="mb-4{{ $errors->has('email') ? ' has-error' : '' }}">
                    <x-forms.label for="email" class="sr-only">E-mail</x-forms.label>
                    <x-forms.input name="email" id="email" placeholder="E-mail" value="{{ old('email') }}" required />

                    @if ($errors->has('email'))
                        <div class="help-block">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>

                <div class="mb-4{{ $errors->has('password') ? ' has-error' : '' }}">
                    <x-forms.label for="password" class="sr-only">Password</x-forms.label>
                    <x-forms.input type="password" name="password" id="password" placeholder="Password" required />

                    @if ($errors->has('password'))
                        <div class="help-block">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>

                <div class="mb-6">
                    <x-forms.label for="password_confirmation" class="sr-only">Confirm password</x-forms.label>
                    <x-forms.input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm password" required />
                </div>

                <div>
                    <x-forms.button>
                        Register
                    </x-forms.button>
                </div>
            </form>
        </div>
    </div>
@endsection
