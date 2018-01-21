<section class="objectlist">
    {!! Form::open(['route' => 'calendar']) !!}
        <section class="objectlist-date input-group">
            {!! Form::text('date', isset($date) ? $date->format('Y-m-d') : now()->format('Y-m-d')) !!}
            {!! Form::submit('Show calendar', ['class' => 'btn btn-primary image-replacement']) !!}
        </section>

        <div class="container">
            <ul>
                @each('partials.object-list.category', $categories, 'category')
                @each('partials.object-list.object', $objects, 'object')
            </ul>
        </div>
    {!! Form::close() !!}

    <a href="#" id="objectlist-toggle" class="objectlist-toggle">
        @svg('chevron-left', ['class' => 'objectlist-toggle-icon'])
        @svg('chevron-right', ['class' => 'objectlist-toggle-icon'])
    </a>
</section>