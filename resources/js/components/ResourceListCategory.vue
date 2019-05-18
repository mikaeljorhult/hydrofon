<template>
    <li v-bind:class="{ 'resourcelist-category': true, 'has-children': hasChildren }">
        <span v-on:click="this.handleClick">
            <icon icon="folder" class="w-5"></icon>
            {{ item.name }}
        </span>

        <ul
            class="list-reset resourcelist-children"
            v-if="hasChildren && expanded"
        >
            <resourcelist-category
                    v-for="category in item.categories"
                    v-bind:item="category"
                    v-bind:key="'category' + category.id"
            ></resourcelist-category>

            <resourcelist-resource
                    v-for="resource in item.resources"
                    v-bind:item="resource"
                    v-bind:key="'resource' + resource.id"
            ></resourcelist-resource>
        </ul>
    </li>
</template>

<script>
    import Icon from 'laravel-mix-vue-svgicon/IconComponent';
    import ResourceListResource from './ResourceListResource';
    import Events from '../modules/events';

    export default {
        name: 'resourcelist-category',
        props: {
            'item': Object,
        },
        data: function () {
            return {
                expanded: this.item.expanded
            };
        },
        computed: {
            hasChildren: function () {
                return (this.item.categories && this.item.categories.length > 0) || (this.item.resources && this.item.resources.length > 0);
            }
        },
        methods: {
            handleClick: function () {
                this.expanded = !this.expanded;

                Events.$emit('categories-expanded', {
                    id: this.item.id,
                    expanded: this.expanded
                });
            }
        },
        components: {
            'icon': Icon,
            'resourcelist-resource': ResourceListResource,
        },
    };
</script>