<div
    id="resourcelist"
    class="resourcelist w-2/3 fixed inset-y-0 z-50 pt-16 overflow-hidden whitespace-nowrap select-none md:w-auto md:relative md:!flex"
    x-data="{
        visible: false,
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

    x-on:show-resourcelist.window="visible = true"
    x-show="visible"
    x-transition:enter="transition ease-in-out duration-300"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in-out duration-300"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    x-cloak
>
    <form action="{{ route('calendar') }}" method="post" class="w-full" x-ref="form">
        @csrf

        <section class="h-16 flex items-center absolute top-0 inset-x-0 px-4 bg-complementary-600">
            <x-forms.input
                name="date"
                value="{{ isset($date) ? $date->format('Y-m-d') : now()->format('Y-m-d') }}"
                x-ref="datepicker"
                x-bind:value="date"
            />

            <x-forms.button class="sr-only">
                Show calendar
            </x-forms.button>
        </section>

        @include('partials.resource-tree.base')
    </form>

    <div
        class="fixed inset-0 z-[-1] bg-gray-600 bg-opacity-75 cursor-pointer md:hidden"
        aria-hidden="true"

        x-on:click="visible = false"
        x-show="visible"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    ></div>
</div>
