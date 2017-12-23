@extends('layouts.app')

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="screen-reader">E-mail</label>
            <input id="email" type="email" name="email" placeholder="E-mail" value="{{ old('email') }}" required>

            @if ($errors->has('email'))
                <div class="help-block">
                    {{ $errors->first('email') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <input type="submit" value="Send reset link">
        </div>
    </form>
@endsection
