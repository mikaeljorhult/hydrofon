<template>
    <td class="text-center">
        <label>
            <input
                v-bind:checked="value"
                v-bind:disabled="isSaving"
                v-on:input="$emit('input', $event.target.checked)"
                type="checkbox"
            />
        </label>
    </td>
</template>

<script>
    export default {
        props: ['index', 'type', 'item', 'column', 'isSaving', 'value'],
    };
</script>
