import flatpickr from 'flatpickr';

export default (initialState) => ({
    start_time: initialState.start_time,
    end_time: initialState.end_time,

    startTimeDatepicker: null,
    endTimeDatepicker: null,

    init() {
        this.startTimeDatepicker = flatpickr(this.$refs.start_time, {
            allowInput: false,
            altFormat: 'Y-m-d H:i',
            dateFormat: 'Y-m-d H:i',
            time_24hr: true,
            enableTime: true,
            onChange: (selectedDates, dateStr, instance) => {
                if (this.endTimeDatepicker.selectedDates[0] < selectedDates[0]) {
                    this.endTimeDatepicker.setDate(selectedDates[0].getTime() + 1000 * 60 * 60 * 2)
                }

                this.endTimeDatepicker.set('minDate', selectedDates[0]);
            },
        });

        this.endTimeDatepicker = flatpickr(this.$refs.end_time, {
            allowInput: false,
            altFormat: 'Y-m-d H:i',
            dateFormat: 'Y-m-d H:i',
            time_24hr: true,
            enableTime: true,
        });
    },

    base: {
        ['x-on:submit.prevent']() {
            this.$root.dispatchEvent(new CustomEvent(
                'createbooking', {
                    detail: {
                        resource_id: this.selected,
                        start_time: (new Date(this.start_time).getTime() - (new Date().getTimezoneOffset() * 60 * 1000)) / 1000,
                        end_time: (new Date(this.end_time).getTime() - (new Date().getTimezoneOffset() * 60 * 1000)) / 1000,
                    }
                }
            ));
        },
    }
})
