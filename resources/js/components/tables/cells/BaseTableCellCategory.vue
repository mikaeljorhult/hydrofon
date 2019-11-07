<template>
    <td>
        <v-select
            class="field p-1"
            placeholder="Category"
            v-model="value"
            v-bind:name="column.prop"
            v-bind:searchable="true"
            v-bind:clearable="false"
            v-bind:options="relationships[column.prop.replace('_id', '')]"
            v-bind:reduce="category => category.id"
            label="name"
        >
            <span slot="open-indicator"></span>
            <span slot="no-options"></span>
        </v-select>
    </td>
</template>

<script>
    import vSelect from 'vue-select';

    export default {
        components: {
            'v-select': vSelect,
        },

        props: ['index', 'resource', 'item', 'column', 'isSaving', 'value'],

        inject: ['relationships'],
    };
</script>
