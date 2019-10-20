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

                <th v-for="column in columns">
                    <a v-bind:href="'/' + resource + '?sort=' + (sort === column.prop ? '-' : '') + column.prop">
                        {{ column.name }}
                    </a>
                </th>

                <th>&nbsp;</th>
            </tr>
        </thead>

        <tbody is="transition-group" name="slide-fade">
            <table-base-row
                v-for="item in items"
                v-bind:key="item.id"
                v-bind:resource="resource"
                v-bind:item="item"
                v-bind:columns="columns"
                v-bind:actions="actions"
                v-bind:isSelected="selectedItems.indexOf(item.id) > -1"
                v-bind:isEditing="editItem === item.id"
                v-bind:isSaving="isSaving"
                v-on:select="selectItem"
                v-on:delete="$emit('delete', $event)"
                v-on:edit="$emit('edit', $event)"
                v-on:save="$emit('save', $event)"
                v-on:cancel="$emit('cancel', $event)"
            ></table-base-row>

            <tr
                v-if="!hasItems"
                key="no-items"
            >
                <td v-bind:colspan="numberOfColumns">No items was found.</td>
            </tr>
        </tbody>

        <tfoot v-if="multipleActions.length > 0 && hasItems">
            <tr>
                <th
                    v-bind:colspan="numberOfColumns"
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
    import BaseTableRow from "./BaseTableRow";

    export default {
        components: {
            'table-base-row': BaseTableRow,
        },

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
            sort: {
                type: String,
                required: false,
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
            editItem: {
                type: Number,
                required: false,
                default: 0,
            },
            isSaving: {
                type: Boolean,
                required: false,
                default: false,
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

            editIndex: function () {
                return this.items.findIndex((item) => this.editItem === item.id);
            },

            hasItems: function () {
                return this.items.length > 0;
            },

            numberOfColumns: function () {
                return this.columns.length + 2;
            },
        },

        watch: {
            selectedItems: function () {
                this.isSelected = this.selectedItems.length === this.items.length;
            },
        },

        methods: {
            selectItem: function (item) {
                let index = this.selectedItems.findIndex(function (selected) {
                    return selected === item.id;
                });

                if (index === -1) {
                    this.selectedItems.push(item.id);
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
