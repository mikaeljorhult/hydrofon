<section class="objectlist">
    <ul>
        @each('partials.object-list.category', $categories, 'category')
        @each('partials.object-list.object', $objects, 'object')
    </ul>

    <a href="#" id="objectlist-toggle" class="objectlist-toggle"></a>
</section>