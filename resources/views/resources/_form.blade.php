<div class="mb-4">
    {!! Form::label('name', 'Name', ['class' => 'label']) !!}
    {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'field' . ($errors->has('name') ? ' is-invalid' : '')]) !!}
</div>

<div class="mb-4">
    {!! Form::label('description', 'Description', ['class' => 'label']) !!}
    {!! Form::textarea('description', null, ['placeholder' => 'Description', 'class' => 'field' . ($errors->has('description') ? ' is-invalid' : '')]) !!}
</div>

@if(\App\Category::exists())
    <div class="mb-6">
        {!! Form::label('categories[]', 'Categories', ['class' => 'label']) !!}
        {!! Form::select('categories[]', \App\Category::orderBy('name')->pluck('name', 'id'), isset($resource) ? $resource->categories->pluck('id') : [], ['multiple' => true, 'class' => 'field' . ($errors->has('categories') ? ' is-invalid' : '')]) !!}
    </div>
@endif

@if(\App\Group::exists())
    <div class="mb-6">
        {!! Form::label('groups[]', 'Groups', ['class' => 'label']) !!}
        {!! Form::select('groups[]', \App\Group::orderBy('name')->pluck('name', 'id'), isset($resource) ? $resource->groups->pluck('id') : [], ['multiple' => true, 'class' => 'field' . ($errors->has('groups') ? ' is-invalid' : '')]) !!}
    </div>
@endif

<div class="mb-4">
    {!! Form::label('is_facility', 'Attributes', ['class' => 'label']) !!}

    <label>
        {!! Form::checkbox('is_facility', 1, null) !!}
        Facility
    </label>
</div>

<div class="mt-6">
    <a href="{{ ($backUrl = session()->get('index-referer-url')) ? $backUrl : request()->headers->get('referer') }}" class="btn btn-link">Cancel</a>
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
</div>
