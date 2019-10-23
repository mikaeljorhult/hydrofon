<template>
    <table-base
        v-bind:resource="resource"
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

    export default {
        components: {
            'table-base': require('./BaseTable').default,
        },

        props: {
            items: {
                type: Array,
                required: false,
                default: function () {
                    return [];
                },
            },
            sort: {
                type: String,
                required: false,
            }
        },

        data: function () {
            return {
                resource: 'categories',
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

        methods: {
            onDelete: function (ids) {
                ids.forEach((id) => {
                    this.$store.dispatch(this.resource + '/delete', id);
                });
            },

            onEdit: function (ids) {
                this.editItem = ids[0];
            },

            onSave: function (item) {
                this.isSaving = true;

                this.$store.dispatch(this.resource + '/update', item).then(() => {
                    this.isSaving = false;
                    this.editItem = 0;
                });
            },

            onCancel: function () {
                this.editItem = 0;
            },
        },

        created: function () {
            if (this.items.length > 0) {
                this.$store.commit(this.resource + '/items', this.items);
            }

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
        },
    };
</script>
