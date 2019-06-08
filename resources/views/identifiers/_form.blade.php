<div class="mb-6">
    {!! Form::label('value', 'Identifier', ['class' => 'label']) !!}
    {!! Form::text('value', null, ['placeholder' => 'Value', 'class' => 'field']) !!}
</div>

<div class="mt-6">
    <a href="{{ ($backUrl = session()->get('index-referer-url')) ? $backUrl : request()->headers->get('referer') }}" class="btn btn-link">Cancel</a>
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
</div>