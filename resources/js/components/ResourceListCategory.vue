<template>
    <li class="resourcelist-category">
        <span>
            <icon icon="folder" class="w-5"></icon>
            {{ item.name }}
        </span>

        <ul
            class="list-reset resourcelist-children"
            v-if="hasChildren"
        >
            <resourcelist-category
                    v-for="category in item.categories"
                    v-bind:item="category"
                    v-bind:key="'category' + category.id"
            ></resourcelist-category>

            <resourcelist-resource
                    v-for="resource in item.resources"
                    v-bind:item="resource"
                    v-bind:key="'category' + resource.id"
            ></resourcelist-resource>
        </ul>
    </li>
</template>

<script>
    import Icon from 'laravel-mix-vue-svgicon/IconComponent';
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
            'icon': Icon,
            'resourcelist-resource': ResourceListResource,
        },
    };
</script>