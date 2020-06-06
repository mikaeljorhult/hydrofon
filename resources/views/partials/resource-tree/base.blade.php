<ul class="resourcelist-base list-none px-4 py-2">
    @each('partials.resource-tree.category', $categories, 'category')
    @each('partials.resource-tree.resource', $resources, 'resource')
</ul>
