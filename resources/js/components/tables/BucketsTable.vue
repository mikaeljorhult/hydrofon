<template>
    <table-base
        v-bind:resource="resource"
        v-bind:items="$store.state.buckets.items"
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
                resource: 'buckets',
                columns: [{
                    type: 'text',
                    name: 'Name',
                    prop: 'name'
                }],
                editItem: 0,
                isSaving: false,
            };
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
        },
    };
</script>
