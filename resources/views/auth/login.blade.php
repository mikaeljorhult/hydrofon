@extends('layouts.guest')

@section('content')
    <div class="w-full h-full flex flex-col items-center justify-center">
        <div class="w-full sm:w-1/2 md:w-1/3">
            <h1 class="mb-4">Log in</h1>

            <form method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <div class="mb-4{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="sr-only">E-mail</label>
                    <input id="email" type="email" name="email" placeholder="E-mail" class="field" value="{{ old('email') }}" required autofocus>

                    @if ($errors->has('email'))
                        <div class="help-block">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>

                <div class="mb-4{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" type="password" name="password"  placeholder="Password" class="field" required>

                    @if ($errors->has('password'))
                        <div class="help-block">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>

                <div class="mb-6">
                    <label class="label">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember me
                    </label>
                </div>

                <div class="flex items-center justify-between">
                    <input type="submit" value="Log in" class="btn btn-block btn-primary">

                    <a href="{{ route('password.request') }}" class="forgot-password">
                        Forgot password?
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
