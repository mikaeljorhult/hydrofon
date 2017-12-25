<div class="form-group">
    {!! Form::label('name', 'Name') !!}
    {!! Form::text('name', null, ['placeholder' => 'Name']) !!}
</div>

<div class="form-group">
    {!! Form::label('email', 'E-mail') !!}
    {!! Form::email('email', null, ['placeholder' => 'E-mail']) !!}
</div>

<div class="form-group">
    {!! Form::label('password', 'Password') !!}
    {!! Form::password('password', null, ['placeholder' => 'Password']) !!}
</div>

<div class="form-group">
    {!! Form::label('password_confirmation', 'Confirm password') !!}
    {!! Form::password('password_confirmation', null, ['placeholder' => 'Confirm password']) !!}
</div>

<div class="form-group">
    <a href="{{ route('users.index') }}" class="btn btn-link">Cancel</a>
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
</div>