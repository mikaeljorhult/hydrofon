<template>
    <table-base
        v-bind:type="type"
        v-bind:items="$store.state.categories.items"
        v-bind:columns="columns"
        v-bind:sort="sort"
        v-bind:editItem="editItem"
        v-bind:isSaving="isSaving"
        v-on:delete="onDelete"
        v-on:edit="onEdit"
        v-on:save="onSave"
        v-on:cancel="onCancel"
    ></table-base>
</template>

<script>
    import axios from 'axios';
    import BaseTableBehaviour from './BaseTableBehaviour';

    export default {
        extends: BaseTableBehaviour,

        props: {
            itemsParent: {
                type: Array,
                required: false,
                default: function () {
                    return [];
                },
            },
        },

        data: function () {
            return {
                type: 'categories',
                columns: [{
                    type: 'text',
                    name: 'Name',
                    prop: 'name',
                    sort: 'categories.name',
                }, {
                    type: 'category',
                    name: 'Parent',
                    prop: 'parent_id',
                    sort: 'parent.name',
                }],
                editItem: 0,
                isSaving: false,
                parent: [],
            };
        },

        reactiveProvide: {
            name: 'relationships',
            include: ['parent'],
        },

        created: function () {
            if (this.items.length > 0) {
                this.$store.commit(this.type + '/items', this.items);
            }

            if (this.itemsParent.length > 0) {
                this.parent = this.itemsParent;
            } else {
                axios.get('api/categories', {
                    params: {
                        'filter[categories.id]': this.$store.state.categories.items.map((item) => item.parent_id).join(','),
                    }
                })
                    .then(response => {
                        this.parent = response.data.data;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        },
    };
</script>
