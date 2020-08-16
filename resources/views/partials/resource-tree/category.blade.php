<li
    class="resourcelist-category{{ in_array($category->id, $expanded) ? ' expanded' : '' }}"
    x-bind:class="{ expanded: expanded.indexOf({{ $category->id }}) > -1 }"
>
    <label
        class="cursor-pointer"
        x-on:click.stop="multipleSelect($event)"
    >
        <input
            type="checkbox"
            name="categories[]"
            value="{{ $category->id }}"
            {{ in_array($category->id, $expanded) ? 'checked="checked"' : '' }}
            class="hidden"
            x-model.number="expanded"
        />

        <x-heroicon-s-folder class="w-5" />
        {{ $category->name }}
    </label>

    @if($category->children->count() > 0 || $category->resources->count() > 0)
        <ul class="list-none resourcelist-children">
            @each('partials.resource-tree.category', $category->children, 'category')
            @each('partials.resource-tree.resource', $category->resources, 'resource')
        </ul>
    @endif
</li>
