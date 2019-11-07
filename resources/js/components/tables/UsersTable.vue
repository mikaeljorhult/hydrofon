<template>
    <table-base
        v-bind:type="type"
        v-bind:items="$store.state.users.items"
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
                type: 'users',
                columns: [{
                    type: 'email',
                    name: 'E-mail',
                    prop: 'email'
                }, {
                    type: 'text',
                    name: 'Name',
                    prop: 'name'
                }, {
                    type: 'checkbox',
                    name: 'Administrator',
                    prop: 'is_admin'
                }],
                editItem: 0,
                isSaving: false,
            };
        },
    };
</script>
