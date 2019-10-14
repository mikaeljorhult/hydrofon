<template>
    <table class="table">
        <thead>
            <tr>
                <th class="table-column-check">
                    <input
                        type="checkbox"
                        v-model="isSelected"
                        v-on:click="selectAll"
                    />
                </th>

                <th v-for="key in columns">
                    {{ key | capitalize }}
                </th>

                <th>&nbsp;</th>
            </tr>
        </thead>

        <tbody is="transition-group" name="slide-fade">
            <tr
                v-for="item in items"
                v-bind:key="item.id"
                v-on:click="selectItem(item.id)"
            >
                <td data-title="&nbsp;">
                    <input
                        type="checkbox"
                        v-model="selectedItems"
                        v-bind:value="item.id"
                    />
                </td>

                <td v-for="(key, index) in columns">
                    <a
                        v-if="index === 0"
                        v-bind:href="'/' + resource + '/' + item.id + '/edit'"
                        v-on:click.stop=""
                    >
                        {{ item[key] }}
                    </a>

                    <span v-else>
                        {{ item[key] }}
                    </span>
                </td>

                <td data-title="&nbsp;" class="table-actions">
                    <form v-for="action in actions">
                        <button
                            v-on:click.prevent.stop="$emit(action.event, [item.id])"
                        >{{ action.title }}</button>
                    </form>
                </td>
            </tr>
        </tbody>

        <tfoot v-if="multipleActions.length > 0">
            <tr>
                <th
                    v-bind:colspan="this.columns.length + 2"
                    class="text-right"
                >
                    <form v-for="action in multipleActions">
                        <button
                            v-bind:disabled="selectedItems.length === 0"
                            v-on:click.prevent.stop="$emit(action.event, selectedItems)"
                            class="btn"
                        >{{ action.title }}</button>
                    </form>
                </th>
            </tr>
        </tfoot>
    </table>
</template>

<script>
    export default {
        props: {
            resource: {
                type: String,
                required: true,
            },
            items: {
                type: Array,
                required: false,
                default: function () {
                    return [];
                },
            },
            columns: {
                type: Array,
                required: true,
            },
            actions: {
                type: Array,
                required: false,
                default: function () {
                    return [
                        { event: 'edit', title: 'Edit' },
                        { event: 'delete', title: 'Delete', multiple: true },
                    ];
                },
            },
        },

        data: function () {
            return {
                selectedItems: [],
                isSelected: false,
            };
        },

        computed: {
            multipleActions: function () {
                return this.actions.filter(action => action.multiple);
            },
        },

        watch: {
            selectedItems: function () {
                this.isSelected = this.selectedItems.length === this.items.length;
            },
        },

        filters: {
            capitalize: function (str) {
                return str.charAt(0).toUpperCase() + str.slice(1);
            },
        },

        methods: {
            selectItem: function (id) {
                let index = this.selectedItems.findIndex(function (selected) {
                    return selected === id;
                });

                if (index === -1) {
                    this.selectedItems.push(id);
                } else {
                    this.selectedItems.splice(index, 1);
                }
            },

            selectAll: function () {
                if (this.isSelected) {
                    this.selectedItems = [];
                } else {
                    this.selectedItems = this.items.map(function (item) {
                        return item.id;
                    });
                }
            },
        },
    };
</script>
