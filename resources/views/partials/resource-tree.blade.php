<div
    id="resourcelist"
    class="resourcelist w-2/3 fixed inset-y-0 z-50 pt-16 overflow-hidden whitespace-nowrap select-none bg-slate-700 md:w-56 md:relative md:!flex"
    x-data="resourceTree({
        visible: false,
        expanded: @json($expanded),
        selected: @json($selected),
        date: '{{ isset($date) ? $date->format('Y-m-d') : now()->format('Y-m-d') }}'
    })"

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

        <section class="h-16 flex items-center absolute top-0 inset-x-0 px-4 bg-slate-600">
            <x-forms.label for="resourcelist-date" class="sr-only">
                Date
            </x-forms.label>

            <x-forms.input
                id="resourcelist-date"
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
