<div class="mb-4">
    {!! Form::label('name', 'Name', ['class' => 'label']) !!}
    {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'field']) !!}
</div>

<div class="mb-4">
    {!! Form::label('description', 'Description', ['class' => 'label']) !!}
    {!! Form::textarea('description', null, ['placeholder' => 'Description', 'class' => 'field']) !!}
</div>

@if(\Hydrofon\Category::exists())
    <div class="mb-6">
        {!! Form::label('categories[]', 'Categories', ['class' => 'label']) !!}
        {!! Form::select('categories[]', \Hydrofon\Category::orderBy('name')->pluck('name', 'id'), isset($resource) ? $resource->categories->pluck('id') : [], ['multiple' => true, 'class' => 'field']) !!}
    </div>
@endif

@if(\Hydrofon\Group::exists())
    <div class="mb-6">
        {!! Form::label('groups[]', 'Groups', ['class' => 'label']) !!}
        {!! Form::select('groups[]', \Hydrofon\Group::orderBy('name')->pluck('name', 'id'), isset($resource) ? $resource->groups->pluck('id') : [], ['multiple' => true, 'class' => 'field']) !!}
    </div>
@endif

<div class="mt-6">
    <a href="{{ request()->headers->get('referer') }}" class="btn btn-link">Cancel</a>
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
</div>