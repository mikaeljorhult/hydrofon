<div
    class="resourcelist"
    x-data="{
        expanded: {{ str_replace("\"", "'", json_encode(array_map('strval', $expanded))) }},
        selected: {{ str_replace("\"", "'", json_encode(array_map('strval', $selected))) }},
    }"
    x-init="
        $watch('expanded', value => {
            if (HYDROFON.Segel.initialized) {
                HYDROFON.Segel.expanded = value;
            }
        });

        $watch('selected', value => {
            if (HYDROFON.Segel.initialized) {
                HYDROFON.Segel.resources = value;
            } else {
                $refs.form.submit();
            }
        });
    "
>
    {!! Form::open(['route' => 'calendar', 'class' => 'w-full', 'x-ref' => 'form']) !!}
        <section class="resourcelist-date">
            {!! Form::text('date', isset($date) ? $date->format('Y-m-d') : now()->format('Y-m-d'), ['class' => 'field']) !!}
            {!! Form::submit('Show calendar', ['class' => 'btn btn-primary screen-reader']) !!}
        </section>

        <ul class="resourcelist-base list-none px-4 py-2">
            @foreach($categories as $category)
                @include('partials.resource-tree.category', ['category' => $category])
            @endforeach

            @foreach($resources as $resource)
                @include('partials.resource-tree.resource', ['resource' => $resource])
            @endforeach
        </ul>
    {!! Form::close() !!}
</div>
