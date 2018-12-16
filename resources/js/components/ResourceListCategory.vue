<template>
    <li class="resourcelist-category">
        <span>
            {{ item.name }}
        </span>

        <ul
            class="list-reset resourcelist-children"
            v-if="hasChildren"
        >
            <resourcelist-category
                    v-for="category in item.categories"
                    v-bind:item="category"
                    v-bind:key="category.id"
            ></resourcelist-category>

            <resourcelist-resource
                    v-for="resource in item.resource"
                    v-bind:item="resource"
                    v-bind:key="resource.id"
            ></resourcelist-resource>
        </ul>
    </li>
</template>

<script>
    import ResourceListResource from './ResourceListResource';

    export default {
        name: 'resourcelist-category',
        props: {
            'item': Object,
        },
        data: function () {
            return {};
        },
        computed: {
            hasChildren: function () {
                return (this.item.categories && this.item.categories.length > 0) || (this.item.resources && this.item.resources.length > 0);
            },
        },
        components: {
            'resourcelist-resource': ResourceListResource,
        },
    };
</script>