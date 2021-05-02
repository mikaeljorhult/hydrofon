<div class="mb-6">
    {!! Form::label('value', 'Identifier', ['class' => 'label']) !!}
    {!! Form::text('value', null, ['placeholder' => 'Value', 'class' => 'field' . ($errors->has('value') ? ' is-invalid' : '')]) !!}
</div>

<div class="mt-6">
    <x-forms.link :href="session()->get('index-referer-url') ?? request()->headers->get('referer')">
        Cancel
    </x-forms.link>
    <x-forms.button>
        {{ $submitButtonText }}
    </x-forms.button>
</div>
