<li class="resourcelist-category">
    <span>
        @svg('folder', 'w-5')
        {{ $category->name }}
    </span>

    @if($category->categories->count() > 0 || $category->resources->count() > 0)
        <ul class="list-reset resourcelist-children">
            @each('partials.resource-list.category', $category->categories, 'category')
            @each('partials.resource-list.resource', $category->resources, 'resource')
        </ul>
    @endif
</li>