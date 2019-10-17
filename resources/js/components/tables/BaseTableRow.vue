<template>
    <tr v-on:click.stop="onClick">
        <td data-title="&nbsp;">
            <input
                type="checkbox"
                v-bind:checked="isSelected"
                v-on:click="onSelect"
            />
        </td>

        <td v-for="(key, index) in columns">
            <template v-if="isEditing">
                <input
                    v-model="editValues[key]"
                    v-bind:disabled="isSaving"
                    type="text"
                    class="field"
                />
            </template>

            <template v-else>
                <a
                    v-if="index === 0"
                    v-bind:href="'/' + resource + '/' + item.id + '/edit'"
                >
                    {{ item[key] }}
                </a>

                <span v-else>
                    {{ item[key] }}
                </span>
            </template>
        </td>

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
                    this.columns.forEach((key) => this.$set(this.editValues, key, this.item[key]));
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
                this.columns.forEach((key) => this.$set(this.editValues, key, null));
            },
        },
    };
</script>
