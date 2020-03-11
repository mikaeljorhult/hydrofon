<li
    class="resourcelist-category{{ in_array($category->id, $expanded) ? ' expanded' : '' }}"
    x-bind:class="{expanded:expanded.indexOf({{$category->id}}) > -1}"
>
    <span
        class="cursor-pointer"
        x-on:click="toggleCategory({{ $category->id }})"
    >
        @svg('folder', 'w-5')
        {{ $category->name }}
    </span>

    @if($category->children->count() > 0 || $category->resources->count() > 0)
        <ul class="list-none resourcelist-children">
            @foreach($category->children as $category)
                @include('partials.resource-tree.category', ['category' => $category])
            @endforeach

            @foreach($category->resources as $resource)
                @include('partials.resource-tree.resource', ['resource' => $resource])
            @endforeach
        </ul>
    @endif
</li>
