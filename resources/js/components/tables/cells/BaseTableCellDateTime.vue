<template>
    <td>
        <flat-pickr
            v-bind:config="datepickerConfig"
            v-bind:disabled="isSaving"
            v-bind:value="dateString"
            v-on:input="handleDateChange"
            class="field"
        ></flat-pickr>
    </td>
</template>

<script>
    import flatPickr from 'vue-flatpickr-component';

    export default {
        props: ['index', 'type', 'item', 'column', 'isSaving', 'value'],

        data: function () {
            return {
                datepickerConfig: {
                    dateFormat: "Y-m-d H:i",
                    enableTime: true,
                    time_24hr: true,
                    locale: {
                        firstDayOfWeek: 1,
                    }
                }
            };
        },

        computed: {
            dateString: function () {
                // TODO: Make sure timezone offset is respected.
                return new Date(this.value * 1000).toLocaleString();
            },
        },

        methods: {
            handleDateChange: function (selectedDate) {
                this.$emit('input', new Date(selectedDate) / 1000);
            }
        },

        components: {
            'flat-pickr': flatPickr,
        }
    };
</script>
