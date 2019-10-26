<template>
    <td>
        <input
            v-bind:value="value"
            v-bind:disabled="isSaving"
            v-on:input="$emit('input', $event.target.value)"
            type="email"
            class="field"
        />
    </td>
</template>

<script>
    export default {
        props: ['index', 'resource', 'item', 'property', 'isSaving', 'value'],
    };
</script>
