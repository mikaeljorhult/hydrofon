<div class="mb-6">
    {!! Form::label('resource_id', 'Resource', ['class' => 'label']) !!}
    {!! Form::select('resource_id', \App\Models\Resource::orderBy('name')->pluck('name', 'id'), isset($booking) ? $booking->resource_id : null, ['placeholder' => 'Select a resource...', 'class' => 'field' . ($errors->has('resource_id') ? ' is-invalid' : '')]) !!}
</div>

<div class="mb-6">
    {!! Form::label('user_id', 'User', ['class' => 'label']) !!}
    {!! Form::select('user_id', \App\Models\User::orderBy('name')->pluck('name', 'id'), isset($booking) ? $booking->user_id : null, ['placeholder' => 'Select a user...', 'class' => 'field' . ($errors->has('user_id') ? ' is-invalid' : '')]) !!}
</div>

<div class="flex flex-wrap mb-6">
    <div class="w-full mb-4 sm:w-1/2 sm:pr-2 sm:m-0">
        {!! Form::label('start_time', 'Start time', ['class' => 'label']) !!}
        {!! Form::text('start_time', null, ['class' => 'field' . ($errors->has('start_time') ? ' is-invalid' : ''), 'placeholder' => 'Start time']) !!}
    </div>

    <div class="w-full sm:w-1/2 sm:pl-2">
        {!! Form::label('end_time', 'End time', ['class' => 'label']) !!}
        {!! Form::text('end_time', null, ['class' => 'field' . ($errors->has('end_time') ? ' is-invalid' : ''), 'placeholder' => 'End time']) !!}
    </div>
</div>

<div class="mt-6">
    <x-forms.link :href="session()->get('index-referer-url') ?? request()->headers->get('referer')">
        Cancel
    </x-forms.link>
    <x-forms.button>
        {{ $submitButtonText }}
    </x-forms.button>
</div>
