<div class="form-group">
    {!! Form::label('name', 'Name') !!}
    {!! Form::text('name', null, ['placeholder' => 'Name']) !!}
</div>

<div class="form-group">
    {!! Form::label('description', 'Description') !!}
    {!! Form::textarea('description', null, ['placeholder' => 'Description']) !!}
</div>

<div class="form-group">
    <a href="{{ route('resources.index') }}" class="btn btn-link">Cancel</a>
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
</div>