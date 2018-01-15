<div class="form-group">
    {!! Form::label('value', 'Identifier') !!}
    {!! Form::text('value', null, ['placeholder' => 'Value']) !!}
</div>

<div class="form-group">
    <a href="{{ route('users.identifiers.index', [$user->id]) }}" class="btn btn-link">Cancel</a>
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
</div>