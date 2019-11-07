<template>
    <table-base
        v-bind:type="type"
        v-bind:items="$store.state.resources.items"
        v-bind:columns="columns"
        v-bind:actions="actions"
        v-bind:sort="sort"
        v-bind:editItem="editItem"
        v-bind:isSaving="isSaving"
        v-on:identifiers="onIdentifiers"
        v-on:delete="onDelete"
        v-on:edit="onEdit"
        v-on:save="onSave"
        v-on:cancel="onCancel"
    ></table-base>
</template>

<script>
    import BaseTableBehaviour from './BaseTableBehaviour';

    export default {
        extends: BaseTableBehaviour,

        data: function () {
            return {
                type: 'resources',
                columns: [{
                    type: 'text',
                    name: 'Name',
                    prop: 'name'
                }, {
                    type: 'text',
                    name: 'Description',
                    prop: 'description'
                }],
                actions: [
                    { event: 'identifiers', title: 'Identifiers' },
                    { event: 'edit', title: 'Edit' },
                    { event: 'delete', title: 'Delete', multiple: true },
                ],
                editItem: 0,
                isSaving: false,
            };
        },

        methods: {
            onIdentifiers: function (ids) {
                window.location = '/resources/' + ids[0] + '/identifiers';
            },
        },
    };
</script>
