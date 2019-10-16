<template>
    <table-base
        v-bind:resource="resource"
        v-bind:items="$store.state.groups.items"
        v-bind:columns="columns"
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
        },

        data: function () {
            return {
                resource: 'groups',
                columns: ['name'],
                editItem: 0,
                isSaving: false,
            };
        },

        methods: {
            onDelete: function (ids) {
                ids.forEach((id) => {
                    this.$store.dispatch('groups/delete', id);
                });
            },

            onEdit: function (ids) {
                this.editItem = ids[0];
            },

            onSave: function (item) {
                this.isSaving = true;

                this.$store.dispatch('groups/update', item).then(() => {
                    this.isSaving = false;
                    this.editItem = 0;
                });
            },

            onCancel: function () {
                this.editItem = 0;
            },
        },

        mounted: function () {
            if (this.items.length > 0) {
                this.$store.commit('groups/items', this.items);
            }
        },
    };
</script>
