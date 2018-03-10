<div class="form-group">
    {!! Form::label('name', 'Name') !!}
    {!! Form::text('name', null, ['placeholder' => 'Name']) !!}
</div>

@if(\Hydrofon\Resource::count() > 0)
    <div class="form-group">
        {!! Form::label('resources[]', 'Resources') !!}
        {!! Form::select('resources[]', \Hydrofon\Resource::orderBy('name')->pluck('name', 'id'), isset($bucket) ? $bucket->resources->pluck('id') : [], ['multiple' => true]) !!}
    </div>
@endif

<div class="form-group">
    <a href="{{ route('buckets.index') }}" class="btn btn-link">Cancel</a>
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
</div>