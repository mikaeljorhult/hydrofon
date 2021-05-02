<div class="mb-4">
    {!! Form::label('name', 'Name', ['class' => 'label']) !!}
    {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'field' . ($errors->has('name') ? ' is-invalid' : '')]) !!}
</div>

<div class="mb-4">
    {!! Form::label('email', 'E-mail', ['class' => 'label']) !!}
    {!! Form::email('email', null, ['placeholder' => 'E-mail', 'class' => 'field' . ($errors->has('email') ? ' is-invalid' : '')]) !!}
</div>

<div class="mb-4">
    {!! Form::label('password', 'Password', ['class' => 'label']) !!}
    {!! Form::password('password', ['placeholder' => 'Password', 'class' => 'field' . ($errors->has('password') ? ' is-invalid' : '')]) !!}
</div>

<div class="mb-4">
    {!! Form::label('password_confirmation', 'Confirm password', ['class' => 'label']) !!}
    {!! Form::password('password_confirmation', ['placeholder' => 'Confirm password', 'class' => 'field' . ($errors->has('password_confirmation') ? ' is-invalid' : '')]) !!}
</div>

@if(\App\Models\Group::exists())
    <div class="mb-6">
        {!! Form::label('groups[]', 'Groups', ['class' => 'label']) !!}
        {!! Form::select('groups[]', \App\Models\Group::orderBy('name')->pluck('name', 'id'), isset($user) ? $user->groups->pluck('id') : [], ['multiple' => true, 'class' => 'field' . ($errors->has('groups') ? ' is-invalid' : '')]) !!}
    </div>
@endif

<div class="mb-4">
    {!! Form::label('is_admin', 'Attributes', ['class' => 'label']) !!}

    <label>
        {!! Form::checkbox('is_admin', 1, null, ['disabled' => isset($user) && $user->is(auth()->user())]) !!}
        Administrator
    </label>
</div>

<div class="mt-6">
    <x-forms.link :href="session()->get('index-referer-url') ?? request()->headers->get('referer')">
        Cancel
    </x-forms.link>
    <x-forms.button>
        {{ $submitButtonText }}
    </x-forms.button>
</div>
