import flatpickr from 'flatpickr';

export default (initialState) => ({
    isOpen: initialState.isOpen ?? false,
    start_time: initialState.start_time,
    end_time: initialState.end_time,
    resource_id: initialState.resource_id ?? 0,
    search: initialState.search ?? '',

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

    outsideClick(event) {
        if (!this.isOpen) {
            return;
        }

        if (!event.target.closest('.flatpickr-calendar')) {
            this.isOpen = false;
        }
    }
})
