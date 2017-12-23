@extends('layouts.app')

@section('content')
    <h2>Register</h2>

    <form method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="screen-reader">Name</label>
            <input id="name" type="text" name="name" placeholder="Name" value="{{ old('name') }}" required autofocus>

            @if ($errors->has('name'))
                <div class="help-block">
                    {{ $errors->first('name') }}
                </div>
            @endif
        </div>

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="screen-reader">E-mail</label>
            <input id="email" type="email" name="email" placeholder="E-mail" value="{{ old('email') }}" required>

            @if ($errors->has('email'))
                <div class="help-block">
                    {{ $errors->first('email') }}
                </div>
            @endif
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="screen-reader">Password</label>
            <input id="password" type="password" name="password" placeholder="Password" required>

            @if ($errors->has('password'))
                <div class="help-block">
                    {{ $errors->first('password') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="password-confirm" class="screen-reader">Confirm password</label>
            <input id="password-confirm" type="password" name="password_confirmation" placeholder="Confirm password" required>
        </div>

        <div class="form-group">
            <input type="submit" value="Register">
        </div>
    </form>
@endsection
