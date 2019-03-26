<template>
    <section v-bind:class="{ resourcelist: true, collapsed: this.collapsed }">
        <section class="resourcelist-date">
            <flat-pickr
                class="field"
                v-bind:config="datepickerConfig"
                v-bind:value="dateString"
                v-on:input="handleDateChange"
            ></flat-pickr>
            <input type="submit" value="Show calendar" class="btn btn-primary screen-reader" />
        </section>

        <ul class="list-reset p-4" v-if="hasChildren">
            <resourcelist-category
                    v-for="category in tree"
                    v-bind:key="'category' + category.id"
                    v-bind:item="category"
            ></resourcelist-category>

            <resourcelist-resource
                    v-for="resource in rootResources"
                    v-bind:item="resource"
                    v-bind:key="'resource' + resource.id"
            ></resourcelist-resource>
        </ul>

        <button id="resourcelist-toggle" class="resourcelist-toggle" v-on:click="collapsed = !collapsed">
            <icon icon="cheveron-left" v-if="!collapsed"></icon>
            <icon icon="cheveron-right" v-if="collapsed"></icon>
        </button>
    </section>
</template>

<script>
    import flatPickr from 'vue-flatpickr-component';
    import Icon from 'laravel-mix-vue-svgicon/IconComponent';
    import ResourceListCategory from './ResourceListCategory';
    import ResourceListResource from './ResourceListResource';
    import Events from '../modules/events';

    export default {
        props: {
            'date': Number,
            'categories': Array,
            'resources': Array,
        },
        data: function () {
            return {
                collapsed: false,
                datepickerConfig: {
                    locale: {
                        firstDayOfWeek: 1
                    }
                }
            };
        },
        computed: {
            dateString: function () {
                return new Date(this.date * 1000).toISOString().split('T')[0];
            },
            hasChildren: function () {
                return (this.categories && this.categories.length > 0) || (this.resources && this.resources.length > 0);
            },
            rootResources: function () {
                return this.resources.filter(resource => resource.categories.length === 0)
            },
            tree: function () {
                let categories = this.categories;

                categories.forEach(treeCategory => {
                    treeCategory.categories = this.categories.filter(category => category.parent === treeCategory.id);
                    treeCategory.resources = this.resources.filter(resource => resource.categories.indexOf(treeCategory.id) > -1);
                });

                return categories.filter(category => category.parent === null);
            }
        },
        methods: {
            handleDateChange: function (selectedDate) {
                Events.$emit('date-changed', new Date(selectedDate) / 1000);
            }
        },
        components: {
            'flat-pickr': flatPickr,
            'icon': Icon,
            'resourcelist-category': ResourceListCategory,
            'resourcelist-resource': ResourceListResource,
        }
    };
</script>