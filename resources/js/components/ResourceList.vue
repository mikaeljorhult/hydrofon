<template>
    <section class="resourcelist">
        <section class="resourcelist-date">
            <input type="text" class="field" v-model="date" />
            <input type="submit" value="Show calendar" class="btn btn-primary screen-reader" />
        </section>

        <ul class="list-reset p-4" v-if="hasChildren">
            <resourcelist-category
                    v-for="category in categories"
                    v-bind:item="category"
                    v-bind:key="'category' + category.id"
            ></resourcelist-category>

            <resourcelist-resource
                    v-for="resource in resources"
                    v-bind:item="resource"
                    v-bind:key="'resource' + resource.id"
            ></resourcelist-resource>
        </ul>

        <a href="#" id="resourcelist-toggle" class="resourcelist-toggle">

        </a>
    </section>
</template>

<script>
    import ResourceListCategory from './ResourceListCategory';
    import ResourceListResource from './ResourceListResource';

    export default {
        props: {
            'date': String,
            'categories': Array,
            'resources': Array,
        },
        data: function () {
            return {};
        },
        computed: {
            hasChildren: function () {
                return (this.categories && this.categories.length > 0) || (this.resources && this.resources.length > 0);
            },
        },
        components: {
            'resourcelist-category': ResourceListCategory,
            'resourcelist-resource': ResourceListResource,
        },
    };
</script>