<div class="form-group">
    {!! Form::label('value', 'Identifier') !!}
    {!! Form::text('value', null, ['placeholder' => 'Value']) !!}
</div>

<div class="form-group">
    <a href="{{ request()->headers->get('referer') }}" class="btn btn-link">Cancel</a>
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
</div>