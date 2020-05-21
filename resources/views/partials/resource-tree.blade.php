<div
    class="resourcelist"
    x-data="{
        expanded: {{ str_replace("\"", "'", json_encode(array_map('strval', $expanded))) }},
        selected: {{ str_replace("\"", "'", json_encode(array_map('strval', $selected))) }},
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
        });

        datepicker = TinyDatePicker($refs.datepicker, {
            mode: 'dp-below',
            format: function (date) {
                return new Date(
                        date.getTime() - (date.getTimezoneOffset() * 60 * 1000)
                    ).toISOString().slice(0,10);
            },
            dayOffset: 1,
        });

        datepicker.on('select', (_, dp) => {
            let newDate = new Date(
                dp.state.selectedDate.getTime() - (dp.state.selectedDate.getTimezoneOffset() * 60 * 1000)
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
            {!! Form::submit('Show calendar', ['class' => 'btn btn-primary screen-reader']) !!}
        </section>

        <ul class="resourcelist-base list-none px-4 py-2">
            @foreach($categories as $category)
                @include('partials.resource-tree.category', ['category' => $category])
            @endforeach

            @foreach($resources as $resource)
                @include('partials.resource-tree.resource', ['resource' => $resource])
            @endforeach
        </ul>
    {!! Form::close() !!}
</div>
