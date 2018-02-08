<li class="resourcelist-category">
    <span>
        @svg('folder')
        {{ $category->name }}
    </span>

    @if($category->categories->count() > 0 || $category->resources->count() > 0)
        <ul class="resourcelist-children">
            @each('partials.resource-list.category', $category->categories, 'category')
            @each('partials.resource-list.resource', $category->resources, 'resource')
        </ul>
    @endif
</li>