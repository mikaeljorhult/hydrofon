<template>
    <tr v-on:click.stop="onClick">
        <td data-title="&nbsp;">
            <input
                type="checkbox"
                v-bind:checked="isSelected"
                v-on:click="onSelect"
            />
        </td>

        <td
            v-for="(column, index) in columns"
            v-bind:is="isEditing ? 'table-base-cell-' + (column.type || 'text') : 'table-base-cell'"
            v-bind:key="index"
            v-model="editValues[column.prop]"
            v-bind:index="index"
            v-bind:resource="resource"
            v-bind:item="item"
            v-bind:column="column"
            v-bind:isSaving="isSaving"
        ></td>

        <td data-title="&nbsp;" class="table-actions">
            <template v-if="isEditing">
                <a
                    v-bind:disabled="isSaving"
                    v-on:click.prevent="$emit('save', { id: item.id, ...editValues })"
                    class="btn btn-primary"
                >{{ isSaving ? 'Saving' : 'Save' }}</a>

                <a
                    v-bind:disabled="isSaving"
                    v-on:click.prevent="$emit('cancel')"
                    class="btn"
                >Cancel</a>
            </template>

            <template v-else>
                <form v-for="action in actions">
                    <button
                        v-on:click.prevent="$emit(action.event, [item.id])"
                    >{{ action.title }}</button>
                </form>
            </template>
        </td>
    </tr>
</template>

<script>
    export default {
        components: {
            'table-base-cell': require('./cells/BaseTableCell').default,
            'table-base-cell-text': require('./cells/BaseTableCellText').default,
            'table-base-cell-category': require('./cells/BaseTableCellCategory').default,
            'table-base-cell-checkbox': require('./cells/BaseTableCellCheckbox').default,
            'table-base-cell-email': require('./cells/BaseTableCellEmail').default,
        },

        props: {
            resource: {
                type: String,
                required: true,
            },
            item: {
                type: Object,
                required: true,
            },
            columns: {
                type: Array,
                required: true,
            },
            actions: {
                type: Array,
                required: true,
            },
            isEditing: {
                type: Boolean,
                required: false,
                default: false,
            },
            isSaving: {
                type: Boolean,
                required: false,
                default: false,
            },
            isSelected: {
                type: Boolean,
                required: false,
                default: false,
            },
        },

        data: function () {
            return {
                editValues: {},
            };
        },

        watch: {
            isEditing: function () {
                if (this.isEditing) {
                    this.columns.forEach((column) => this.$set(this.editValues, column.prop, this.item[column.prop]));
                } else {
                    this.resetEdit();
                }
            },
        },

        methods: {
            onClick: function (event) {
                if (['tr', 'td'].indexOf(event.target.tagName.toLowerCase()) > -1) {
                    this.onSelect();
                }
            },

            onSelect: function () {
                this.$emit('select', { id: this.item.id, checked: !this.isSelected });
            },

            resetEdit: function () {
                this.columns.forEach((column) => this.$set(this.editValues, column.prop, null));
            },
        },
    };
</script>
