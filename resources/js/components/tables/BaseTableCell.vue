<template>
    <td>
        <a
            v-if="index === 0"
            v-bind:href="url"
        >
            {{ item[property] }}
        </a>

        <span v-else>
            {{ item[property] }}
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
        },
    };
</script>
