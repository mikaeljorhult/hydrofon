<div class="mb-4">
    {!! Form::label('name', 'Name', ['class' => 'label']) !!}
    {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'field']) !!}
</div>

<div class="mb-6">
    {!! Form::label('parent_id', 'Parent', ['class' => 'label']) !!}
    {!! Form::text('parent_id', null, ['placeholder' => 'Parent', 'class' => 'field']) !!}
</div>

<div class="mt-6">
    <a href="{{ request()->headers->get('referer') }}" class="btn btn-link">Cancel</a>
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
</div>