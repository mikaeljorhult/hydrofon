<div
    class="resourcelist"
    x-data="{
        expanded: @json($expanded),
        selected: @json($selected),
        date: '{{ isset($date) ? $date->format('Y-m-d') : now()->format('Y-m-d') }}',
        datepicker: null,
        multipleSelect: function ($event) {
            if (!$event.target.classList.contains('hidden') && $event.altKey) {
                var category = $event.target.closest('.resourcelist-category');
                var checkboxes = Array.from(category.querySelectorAll(':scope > .resourcelist-children > .resourcelist-resource input'));
                var checked = checkboxes.filter(item => item.checked);

                if (checkboxes.length === checked.length) {
                    var itemsToDeselect = checked.map(item => item.value);

                    this.selected = this.selected.filter(id => itemsToDeselect.indexOf(id) === -1);
                } else {
                    var itemsToSelect = checkboxes
                        .filter(item => !item.checked)
                        .map(item => item.value);

                    this.selected = this.selected.concat(itemsToSelect);
                }

                $event.preventDefault();
            };
        },
    }"
    x-init="
        $watch('expanded', value => {
            if (HYDROFON.Segel.initialized) {
                HYDROFON.Segel.expanded = value;
            }
        });

        $watch('selected', value => {
            if (HYDROFON.Segel.initialized) {
                HYDROFON.Segel.resources = value;
            } else {
                $refs.form.submit();
            }
        });

        window.livewire.on('dateChanged', (state) => {
            date = state.date;
            datepicker.setDate(date, false);
        });

        datepicker = flatpickr($refs.datepicker, {
            allowInput: true,
            altFormat: 'Y-m-d',
            dateFormat: 'Y-m-d',
            time_24hr: true,
            onChange: function(selectedDates, dateStr, instance) {
                let newDate = new Date(
                    selectedDates[0].getTime() - (selectedDates[0].getTimezoneOffset() * 60 * 1000)
                ).getTime() / 1000;

                if (HYDROFON.Segel.initialized) {
                    let timestamps = {
                        start: newDate,
                        end: newDate + HYDROFON.Segel.component.data.timestamps.duration,
                        duration: HYDROFON.Segel.component.data.timestamps.duration,
                    };

                    HYDROFON.Segel.component.call('setTimestamps', timestamps);
                } else {
                    $refs.form.submit();
                }
            },
        });
    "
>
    {!! Form::open(['route' => 'calendar', 'class' => 'w-full', 'x-ref' => 'form']) !!}
        <section class="resourcelist-date">
            <input
                type="text"
                name="date"
                class="field"
                value="{{ isset($date) ? $date->format('Y-m-d') : now()->format('Y-m-d') }}"
                x-ref="datepicker"
                x-bind:value="date"
            />
            <x-forms.button class="sr-only">
                Show calendar
            </x-forms.button>
        </section>

        @include('partials.resource-tree.base')
    {!! Form::close() !!}
</div>
