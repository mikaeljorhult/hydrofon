import { Livewire } from '../../../vendor/livewire/livewire/dist/livewire.esm';
import interact from 'interactjs';
import Store from '../store';

export default (initialState) => ({
    visible: initialState.visible ?? true,
    expanded: initialState.expanded ?? [],
    selected: initialState.selected ?? [],
    date: initialState.date,

    datepicker: null,
    debounceExpanded: null,
    debounceSelected: null,

    multipleSelect($event) {
        if (!$event.target.classList.contains('hidden') && $event.altKey) {
            const category = $event.target.closest('.resourcelist-category');
            const checkboxes = Array.from(category.querySelectorAll(':scope > .resourcelist-children > .resourcelist-resource input'));
            const checked = checkboxes.filter(item => item.checked);

            if (checkboxes.length === checked.length) {
                let itemsToDeselect = checked.map(item => item.value);

                this.selected = this.selected.filter(id => itemsToDeselect.indexOf(id) === -1);
            } else {
                let itemsToSelect = checkboxes
                    .filter(item => !item.checked)
                    .map(item => item.value);

                this.selected = this.selected.concat(itemsToSelect);
            }

            $event.preventDefault();
        }
    },

    init() {
        this.$watch('expanded', value => {
            if (Store.initialized) {
                clearTimeout(this.debounceExpanded);

                this.debounceExpanded = setTimeout(() => {
                    this.$dispatch('segel-setexpanded', value);
                }, 1000);
            }
        });

        this.$watch('selected', value => {
            clearTimeout(this.debounceSelected);

            this.debounceSelected = setTimeout(() => {
                if (Store.initialized) {
                    this.$dispatch('segel-setresources', value);
                } else {
                    this.$refs.form.submit();
                }
            }, 1000);
        });

        Livewire.on('dateChanged', (state) => {
            this.date = state.date;
            this.datepicker.setDate(this.date, false);
        });

        this.datepicker = flatpickr(this.$refs.datepicker, {
            allowInput: true,
            altFormat: 'Y-m-d',
            dateFormat: 'Y-m-d',
            time_24hr: true,
            onChange: (selectedDates, dateStr, instance) => {
                let newDate = new Date(
                    selectedDates[0].getTime() - (selectedDates[0].getTimezoneOffset() * 60 * 1000)
                ).getTime() / 1000;

                if (Store.initialized) {
                    this.$dispatch('segel-settimestamps', {
                        start: newDate,
                    });
                } else {
                    this.$refs.form.submit();
                }
            },
        });

        if (window.innerWidth > 640) {
            interact(this.$el).resizable({
                edges: { top: false, left: false, bottom: false, right: true, },
                listeners: {
                    move: function (event) {
                        let width = event.rect.width < 140
                            ? '140px'
                            : `${event.rect.width}px`;

                        Object.assign(event.target.style, {
                            width: width,
                        });

                        // Store size in session storage.
                        sessionStorage.setItem('resource-tree-width', width);

                        // Dispatch resize event to make Segel recalculate.
                        window.dispatchEvent(new Event('resize'));
                    }
                }
            });

            this.$el.style.width = sessionStorage.getItem('resource-tree-width') || '14rem';
        }
    },
})
