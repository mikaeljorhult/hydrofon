<template>
    <td v-bind:class="{ 'text-center': column.type === 'checkbox' }">
        <template v-if="column.type === 'checkbox'">
            <input
                v-bind:checked="value"
                type="checkbox"
                disabled="disabled"
            />
        </template>

        <template v-else>
            <a
                v-if="index === 0"
                v-bind:href="url"
            >
                {{ value }}
            </a>

            <span v-else>
                {{ value }}
            </span>
        </template>
    </td>
</template>

<script>
    export default {
        props: ['index', 'type', 'item', 'column', 'isSaving'],

        computed: {
            url: function () {
                return '/' + this.type + '/' + this.item.id + '/edit';
            },

            value: function () {
                return this.column.prop.indexOf('_id') > -1 && this.relationships[this.column.prop.replace('_id', '')].length > 0
                    ? this.relationships[this.column.prop.replace('_id', '')].find((item) => item.id === this.item[this.column.prop]).name
                    : this.item[this.column.prop];
            },
        },

        inject: ['relationships'],
    };
</script>
