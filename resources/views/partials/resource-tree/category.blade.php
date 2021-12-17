<li
    class="resourcelist-category my-1{{ in_array($category->id, $expanded) ? ' expanded' : '' }}"
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

        <x-heroicon-s-folder
            class="resource-list-folder-icon resource-list-folder-closed inline-block w-5 h-auto align-text-bottom fill-red-600 {{ in_array($category->id, $expanded) ? 'hidden' : '' }}"
            x-bind:class="{ hidden: expanded.indexOf({{ $category->id }}) > -1 }"
        />
        <x-heroicon-s-folder-open
            class="resource-list-folder-icon resource-list-folder-open inline-block w-5 h-auto align-text-bottom fill-red-600 {{ in_array($category->id, $expanded) ? '' : 'hidden' }}"
            x-bind:class="{ hidden: expanded.indexOf({{ $category->id }}) == -1 }"
        />
        {{ $category->name }}
    </label>

    @if($category->children->isNotEmpty() || $category->resources->isNotEmpty())
        <ul class="list-none resourcelist-children">
            @each('partials.resource-tree.category', $category->children, 'category')
            @each('partials.resource-tree.resource', $category->resources, 'resource')
        </ul>
    @endif
</li>
