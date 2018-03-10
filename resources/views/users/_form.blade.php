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
    {!! Form::password('password', ['placeholder' => 'Password']) !!}
</div>

<div class="form-group">
    {!! Form::label('password_confirmation', 'Confirm password') !!}
    {!! Form::password('password_confirmation', ['placeholder' => 'Confirm password']) !!}
</div>

@if(\Hydrofon\Group::count() > 0)
    <div class="form-group">
        {!! Form::label('groups', 'Groups') !!}
        {!! Form::select('groups', \Hydrofon\Group::pluck('name', 'id'), isset($user) ? $user->groups->pluck('id') : [], ['multiple' => true]) !!}
    </div>
@endif

<div class="form-group">
    <a href="{{ request()->headers->get('referer') }}" class="btn btn-link">Cancel</a>
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
</div>