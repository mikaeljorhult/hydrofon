<div
    class="resourcelist"
    x-data="{
        expanded: @json($expanded),
        selected: @json($selected),
        toggleCategory: function(id) {
            let index = this.expanded.indexOf(id);

            if (index === -1) {
                this.expanded.push(id);
            } else {
                this.expanded.splice(index, 1);
            }

            HYDROFON.Segel.expanded = this.expanded;
        }
    }"
    x-init="$watch('selected', function(value) { HYDROFON.Segel.resources = value; })"
>
    {!! Form::open(['route' => 'calendar', 'class' => 'w-full']) !!}
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
