@extends('layouts.app')

@section('content')
    <div class="narrow-content">
        <h1>Log in</h1>

        <form method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="screen-reader">E-mail</label>
                <input id="email" type="email" name="email" placeholder="E-mail" value="{{ old('email') }}" required autofocus>

                @if ($errors->has('email'))
                    <div class="help-block">
                        {{ $errors->first('email') }}
                    </div>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="screen-reader">Password</label>
                <input id="password" type="password" name="password"  placeholder="Password" required>

                @if ($errors->has('password'))
                    <div class="help-block">
                        {{ $errors->first('password') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label class="input">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember me
                </label>
            </div>

            <div class="form-group">
                <input type="submit" value="Log in" class="btn btn-block btn-primary">

                <a href="{{ route('password.request') }}" class="forgot-password">
                    Forgot password?
                </a>
            </div>
        </form>
    </div>
@endsection
