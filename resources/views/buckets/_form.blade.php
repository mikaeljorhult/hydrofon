<div class="mb-4">
    {!! Form::label('name', 'Name', ['class' => 'label']) !!}
    {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'field' . ($errors->has('name') ? ' is-invalid' : '')]) !!}
</div>

@if(\App\Models\Resource::exists())
    <div class="mb-6">
        {!! Form::label('resources[]', 'Resources', ['class' => 'label']) !!}
        {!! Form::select('resources[]', \App\Models\Resource::orderBy('name')->pluck('name', 'id'), isset($bucket) ? $bucket->resources->pluck('id') : [], ['multiple' => true, 'class' => 'field' . ($errors->has('resources') ? ' is-invalid' : '')]) !!}
    </div>
@endif

<div class="mt-6">
    <x-forms.link :href="session()->get('index-referer-url') ?? request()->headers->get('referer')">
        Cancel
    </x-forms.link>
    <x-forms.button>
        {{ $submitButtonText }}
    </x-forms.button>
</div>
