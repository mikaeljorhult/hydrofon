<div class="mb-4">
    {!! Form::label('name', 'Name', ['class' => 'label']) !!}
    {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'field' . ($errors->has('name') ? ' is-invalid' : '')]) !!}
</div>

@if(\App\Models\Category::exists())
    <div class="mb-6">
        {!! Form::label('parent_id', 'Parent', ['class' => 'label']) !!}
        {!! Form::select('parent_id', \App\Models\Category::where('id', '!=', isset($category) ? $category->id : 0)->orderBy('name')->pluck('name', 'id'), isset($category) ? $category->parent_id : null, ['placeholder' => 'Select a parent...', 'class' => 'field' . ($errors->has('parent_id') ? ' is-invalid' : '')]) !!}
    </div>
@endif

@if(\App\Models\Group::exists())
    <div class="mb-6">
        {!! Form::label('groups[]', 'Groups', ['class' => 'label']) !!}
        {!! Form::select('groups[]', \App\Models\Group::orderBy('name')->pluck('name', 'id'), isset($category) ? $category->groups->pluck('id') : [], ['multiple' => true, 'class' => 'field' . ($errors->has('groups') ? ' is-invalid' : '')]) !!}
    </div>
@endif

<div class="mt-6">
    <a href="{{ ($backUrl = session()->get('index-referer-url')) ? $backUrl : request()->headers->get('referer') }}" class="btn btn-link">Cancel</a>
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
</div>
