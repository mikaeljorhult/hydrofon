<template>
    <table-base
        v-bind:resource="resource"
        v-bind:items="$store.state.groups.items"
        v-bind:columns="columns"
        v-bind:editItem="editItem"
        v-bind:isSaving="isSaving"
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
                storedItems: this.items,
                editItem: 0,
                isSaving: false,
            };
        },

        methods: {
            onEdit: function (ids) {
                this.editItem = ids[0];
            },

            onSave: function (item) {
                this.isSaving = true;

                this.$store.dispatch('groups/update', item);

                this.isSaving = false;
                this.editItem = 0;
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
