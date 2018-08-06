<div class="mb-4">
    {!! Form::label('resource_id', 'Resource', ['class' => 'label']) !!}
    {!! Form::text('resource_id', null, ['class' => 'field', 'placeholder' => 'Resource']) !!}
</div>

<div class="mb-4">
    {!! Form::label('user_id', 'User', ['class' => 'label']) !!}
    {!! Form::text('user_id', null, ['class' => 'field', 'placeholder' => 'User']) !!}
</div>

<div class="flex flex-wrap mb-6">
    <div class="w-full mb-4 sm:w-1/2 sm:pr-2 sm:m-0">
        {!! Form::label('start_time', 'Start time', ['class' => 'label']) !!}
        {!! Form::text('start_time', null, ['class' => 'field', 'placeholder' => 'Start time']) !!}
    </div>

    <div class="w-full sm:w-1/2 sm:pl-2">
        {!! Form::label('end_time', 'End time', ['class' => 'label']) !!}
        {!! Form::text('end_time', null, ['class' => 'field', 'placeholder' => 'End time']) !!}
    </div>
</div>

<div class="mt-6">
    <a href="{{ request()->headers->get('referer') }}" class="btn btn-link">Cancel</a>
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
</div>