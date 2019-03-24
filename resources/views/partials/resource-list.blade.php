@push('initial-json')
    window.HYDROFON.categories = @json($categories);
    window.HYDROFON.resources = @json($categories->nested('resources', 'categories', false)->merge($resources));
@endpush

<resourcelist-root
        class="resourcelist"
        :date='date'
        :categories='categories'
        :resources='resources'
>
    {!! Form::open(['route' => 'calendar']) !!}
        <section class="resourcelist-date">
            {!! Form::text('date', isset($date) ? $date->format('Y-m-d') : now()->format('Y-m-d'), ['class' => 'field']) !!}
            {!! Form::submit('Show calendar', ['class' => 'btn btn-primary screen-reader']) !!}
        </section>

        <ul class="list-reset p-4">
            @each('partials.resource-list.category', $categories, 'category')
            @each('partials.resource-list.resource', $resources, 'resource')
        </ul>
    {!! Form::close() !!}
</resourcelist-root>