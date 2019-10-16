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
                    <template v-if="editItem === item.id">
                        <input
                            v-model="editValues[key]"
                            v-bind:disabled="isSaving"
                            v-on:click.stop=""
                            type="text"
                            class="field"
                        />
                    </template>

                    <template v-else>
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
                    </template>
                </td>

                <td data-title="&nbsp;" class="table-actions">
                    <template v-if="editItem === item.id">
                        <a
                            v-bind:disabled="isSaving"
                            v-on:click.prevent.stop="$emit('save', { id: item.id, ...editValues })"
                            class="btn btn-primary"
                        >{{ isSaving ? 'Saving' : 'Save' }}</a>

                        <a
                            v-bind:disabled="isSaving"
                            v-on:click.prevent.stop="$emit('cancel')"
                            class="btn"
                        >Cancel</a>
                    </template>

                    <template v-else>
                        <form v-for="action in actions">
                            <button
                                v-on:click.prevent.stop="$emit(action.event, [item.id])"
                            >{{ action.title }}</button>
                        </form>
                    </template>
                </td>
            </tr>

            <tr
                v-if="!hasItems"
                key="no-items"
            >
                <td v-bind:colspan="numberOfColumns">No groups was found.</td>
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
                editValues: {},
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

            editItem: function () {
                if (this.editItem !== 0) {
                    this.columns.forEach((key) => this.$set(this.editValues, key, this.items[this.editIndex][key]));
                } else {
                    this.resetEdit();
                }
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

            resetEdit: function () {
                this.columns.forEach((key) => this.$set(this.editValues, key, null));
            },
        },
    };
</script>
