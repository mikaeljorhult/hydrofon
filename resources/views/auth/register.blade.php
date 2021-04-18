@extends('layouts.guest')

@section('content')
    <div class="w-full h-full flex flex-col items-center justify-center">
        <div class="w-full sm:w-1/2 md:w-1/3">
            <h1 class="mb-4">Register</h1>

            <form method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}

                <div class="mb-4{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="label sr-only">Name</label>
                    <input id="name" type="text" name="name" placeholder="Name" class="field" value="{{ old('name') }}" required autofocus>

                    @if ($errors->has('name'))
                        <div class="help-block">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>

                <div class="mb-4{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="label sr-only">E-mail</label>
                    <input id="email" type="email" name="email" placeholder="E-mail" class="field" value="{{ old('email') }}" required>

                    @if ($errors->has('email'))
                        <div class="help-block">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>

                <div class="mb-4{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="label sr-only">Password</label>
                    <input id="password" type="password" name="password" placeholder="Password" class="field" required>

                    @if ($errors->has('password'))
                        <div class="help-block">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>

                <div class="mb-6">
                    <label for="password-confirm" class="label sr-only">Confirm password</label>
                    <input id="password-confirm" type="password" name="password_confirmation" placeholder="Confirm password" class="field" required>
                </div>

                <div>
                    <input type="submit" value="Register" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
@endsection
