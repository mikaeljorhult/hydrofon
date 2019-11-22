<template>
    <table-base
        v-bind:type="type"
        v-bind:items="$store.state.bookings.items"
        v-bind:columns="columns"
        v-bind:actions="actions"
        v-bind:sort="sort"
        v-bind:editItem="editItem"
        v-bind:isSaving="isSaving"
        v-on:checkout="onCheckout"
        v-on:checkin="onCheckin"
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

        props: {
            itemsResource: {
                type: Array,
                required: false,
                default: function () {
                    return [];
                },
            },
            itemsUser: {
                type: Array,
                required: false,
                default: function () {
                    return [];
                },
            },
        },

        data: function () {
            return {
                type: 'bookings',
                columns: [{
                    type: 'resource',
                    name: 'Resource',
                    prop: 'resource',
                    sort: 'resources.name',
                    relationship: true,
                }, {
                    type: 'user',
                    name: 'User',
                    prop: 'user',
                    sort: 'users.name',
                    relationship: true,
                }, {
                    type: 'datetime',
                    name: 'Start',
                    prop: 'start',
                    sort: 'start_time',
                }, {
                    type: 'datetime',
                    name: 'End',
                    prop: 'end',
                    sort: 'end_time',
                }],
                actions: [
                    { event: 'checkout', title: 'Check out', multiple: true },
                    { event: 'checkin', title: 'Check in', multiple: true },
                    { event: 'view', title: 'View' },
                    { event: 'edit', title: 'Edit' },
                    { event: 'delete', title: 'Delete', multiple: true },
                ],
                editItem: 0,
                isSaving: false,
            };
        },

        reactiveProvide: {
            name: 'relationships',
            include: ['resource', 'user'],
        },

        methods: {
            onCheckout: function (ids) {
                // TODO: Add store actions.
            },

            onCheckin: function (ids) {
                // TODO: Add store actions.
            },
        },

        created: function () {
            if (this.items.length > 0) {
                this.$store.commit(this.type + '/items', this.items);
            }

            if (this.itemsResource.length > 0) {
                this.resource = this.itemsResource;
            } else {
                axios.get('api/resources', {
                    params: {
                        'filter[resource_id]': this.$store.state.resources.items.map((item) => item.resource).join(','),
                    }
                })
                    .then(response => {
                        this.resource = response.data.data;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }

            if (this.itemsUser.length > 0) {
                this.user = this.itemsUser;
            } else {
                axios.get('api/users', {
                    params: {
                        'filter[user_id]': this.$store.state.users.items.map((item) => item.user).join(','),
                    }
                })
                    .then(response => {
                        this.user = response.data.data;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        },
    };
</script>
