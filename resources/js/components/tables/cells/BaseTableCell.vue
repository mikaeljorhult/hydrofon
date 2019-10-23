<template>
    <td>
        <a
            v-if="index === 0"
            v-bind:href="url"
        >
            {{ value }}
        </a>

        <span v-else>
            {{ value }}
        </span>
    </td>
</template>

<script>
    export default {
        props: ['index', 'resource', 'item', 'property', 'isSaving'],

        computed: {
            url: function () {
                return '/' + this.resource + '/' + this.item.id + '/edit';
            },

            value: function () {
                //return this.item[this.property];
                return this.property.indexOf('_id') > -1 && this.relationships[this.property.replace('_id', '')].length > 0
                    ? this.relationships[this.property.replace('_id', '')].find((item) => item.id === this.item[this.property]).name
                    : this.item[this.property];
            },
        },

        inject: ['relationships'],
    };
</script>
