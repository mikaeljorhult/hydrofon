<div class="mb-6">
    {!! Form::label('name', 'Name', ['class' => 'label']) !!}
    {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'field']) !!}
</div>

<div class="mt-6">
    <a href="{{ ($backUrl = session()->get('index-referer-url')) ? $backUrl : request()->headers->get('referer') }}" class="btn btn-link">Cancel</a>
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
</div>