<ul class="resourcelist-base h-full overflow-x-hidden overflow-y-scroll list-none px-4 py-2 pb-8 text-white text-opacity-60">
    @each('partials.resource-tree.category', $categories, 'category')
    @each('partials.resource-tree.resource', $resources, 'resource')
</ul>
