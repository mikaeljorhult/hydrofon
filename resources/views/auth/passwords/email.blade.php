@extends('layouts.app')

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="w-full h-full flex flex-col items-center justify-center">
        <div class="w-full sm:w-1/2 md:w-1/3">
            <h1 class="mb-4">Password Reset</h1>

            <form method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}

                <div class="mb-6{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="screen-reader">E-mail</label>
                    <input id="email" type="email" name="email" placeholder="E-mail" class="field" value="{{ old('email') }}" required>

                    @if ($errors->has('email'))
                        <div class="help-block">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>

                <div>
                    <input type="submit" value="Send reset link" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
@endsection
