<template>
    <td>
        <v-select
            class="field p-1"
            placeholder="Resource"
            v-model="value"
            v-bind:name="column.prop"
            v-bind:searchable="true"
            v-bind:clearable="false"
            v-bind:options="relationships[column.prop.replace('_id', '')]"
            v-bind:reduce="resource => resource.id"
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

        props: ['index', 'type', 'item', 'column', 'isSaving', 'value'],

        inject: ['relationships'],
    };
</script>
