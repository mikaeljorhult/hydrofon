<section class="objectlist">
    {!! Form::open(['route' => 'calendar']) !!}
        <section class="objectlist-date">
            {!! Form::text('date', isset($date) ? $date->format('Y-m-d') : now()->format('Y-m-d')) !!}
            {!! Form::submit('Show calendar', ['class' => 'btn btn-block btn-primary']) !!}
        </section>

        <div class="container">
            <ul>
                @each('partials.object-list.category', $categories, 'category')
                @each('partials.object-list.object', $objects, 'object')
            </ul>
        </div>
    {!! Form::close() !!}

    <a href="#" id="objectlist-toggle" class="objectlist-toggle"></a>
</section>