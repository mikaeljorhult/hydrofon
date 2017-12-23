@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ route('password.request') }}">
        {{ csrf_field() }}

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email">E-mail</label>
            <input id="email" type="email" name="email" value="{{ $email or old('email') }}" required autofocus>

            @if ($errors->has('email'))
                <div class="help-block">
                    {{ $errors->first('email') }}
                </div>
            @endif
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" placeholder="Password" required>

            @if ($errors->has('password'))
                <div class="help-block">
                    {{ $errors->first('password') }}
                </div>
            @endif
        </div>

        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
            <label for="password-confirm">Confirm password</label>
            <input id="password-confirm" type="password" name="password_confirmation" placeholder="Confirm password" required>

            @if ($errors->has('password_confirmation'))
                <div class="help-block">
                    {{ $errors->first('password_confirmation') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <input type="submit" value="Reset password">
        </div>
    </form>
@endsection
