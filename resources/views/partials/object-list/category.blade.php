<li class="objectlist-category">
    <span>
        @svg('folder')
        {{ $category->name }}
    </span>

    @if($category->categories->count() > 0 || $category->objects->count() > 0)
        <ul class="objectlist-children">
            @each('partials.object-list.category', $category->categories, 'category')
            @each('partials.object-list.object', $category->objects, 'object')
        </ul>
    @endif
</li>