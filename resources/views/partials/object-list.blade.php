<section class="objectlist">
    {!! Form::open(['route' => 'calendar']) !!}
        <ul>
            @each('partials.object-list.category', $categories, 'category')
            @each('partials.object-list.object', $objects, 'object')
        </ul>

        {!! Form::text('date', isset($date) ? $date->format('Y-m-d') : now()->format('Y-m-d')) !!}
        {!! Form::submit('Show calendar') !!}
    {!! Form::close() !!}

    <a href="#" id="objectlist-toggle" class="objectlist-toggle"></a>
</section>