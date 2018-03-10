<div class="form-group">
    {!! Form::label('name', 'Name') !!}
    {!! Form::text('name', null, ['placeholder' => 'Name']) !!}
</div>

<div class="form-group">
    {!! Form::label('description', 'Description') !!}
    {!! Form::textarea('description', null, ['placeholder' => 'Description']) !!}
</div>

@if(\Hydrofon\Group::count() > 0)
    <div class="form-group">
        {!! Form::label('groups[]', 'Groups') !!}
        {!! Form::select('groups[]', \Hydrofon\Group::orderBy('name')->pluck('name', 'id'), isset($resource) ? $resource->groups->pluck('id') : [], ['multiple' => true]) !!}
    </div>
@endif

<div class="form-group">
    <a href="{{ route('resources.index') }}" class="btn btn-link">Cancel</a>
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
</div>