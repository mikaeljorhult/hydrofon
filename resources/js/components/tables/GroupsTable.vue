<template>
    <table-base
        v-bind:resource="resource"
        v-bind:items="$store.state.groups.items"
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
    import BaseTableBehaviour from './BaseTableBehaviour';

    export default {
        extends: BaseTableBehaviour,

        data: function () {
            return {
                resource: 'groups',
                columns: [{
                    type: 'text',
                    name: 'Name',
                    prop: 'name'
                }],
                editItem: 0,
                isSaving: false,
            };
        },
    };
</script>
