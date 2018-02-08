<div class="form-group">
    {!! Form::label('resource_id', 'Resource') !!}
    {!! Form::text('resource_id', null, ['placeholder' => 'Resource']) !!}
</div>

<div class="form-group">
    {!! Form::label('user_id', 'User') !!}
    {!! Form::text('user_id', null, ['placeholder' => 'User']) !!}
</div>

<div class="form-group">
    {!! Form::label('start_time', 'Start time') !!}
    {!! Form::text('start_time', null, ['placeholder' => 'Start time']) !!}
</div>

<div class="form-group">
    {!! Form::label('end_time', 'End time') !!}
    {!! Form::text('end_time', null, ['placeholder' => 'End time']) !!}
</div>

<div class="form-group">
    <a href="{{ route('bookings.index') }}" class="btn btn-link">Cancel</a>
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
</div>