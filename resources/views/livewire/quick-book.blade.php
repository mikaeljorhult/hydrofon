<div
    class="mx-1 relative"
    x-data="quickBook({
        start_time: @entangle('start_time'),
        end_time: @entangle('end_time'),
        resource_id: @entangle('resource_id')
    })"
    x-on:click.outside="outsideClick"
>
    <div>
        <button
            type="button"
            class="group relative bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-300"
            id="quickbook-menu-button"
            aria-expanded="false"
            aria-haspopup="true"
            x-on:click.prevent="isOpen = !isOpen"
            wire:click.prefetch="loadResources"
        >
            <span class="sr-only">Open Quick Book</span>
            <x-heroicon-o-lightning-bolt class="w-6 h-6" />
        </button>
    </div>

    <div
        class="origin-top-right absolute right-0 mt-2 w-72 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
        role="menu"
        aria-orientation="vertical"
        aria-labelledby="quickbook-menu-button"
        tabindex="-1"

        x-show="isOpen"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-cloak
    >
        <div class="py-2 px-4">
            <form wire:submit.prevent="book">
                <h3 class="mb-2 text-base">Quick Book</h3>

                <div class="mb-2">
                    <x-forms.label for="quickbook-start_time" class="sr-only">Start Time</x-forms.label>
                    <x-forms.input
                        id="quickbook-start_time"
                        name="start_time"
                        placeholder="Start Time"
                        x-model.lazy="start_time"
                        x-ref="start_time"
                    />
                    @error('start_time')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <x-forms.label for="quickbook-end_time" class="sr-only">Start Time</x-forms.label>
                    <x-forms.input
                        id="quickbook-end_time"
                        name="end_time"
                        placeholder="End Time"
                        x-model.lazy="end_time"
                        x-ref="end_time"
                    />
                    @error('end_time')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex mb-2">
                    <x-forms.label for="quickbook-resources" class="sr-only">Resources</x-forms.label>
                    <x-forms.select
                        id="quickbook-resources"
                        name="resource_id"
                        :options="$this->availableResources->count() > 0 ? $this->availableResources->pluck('name', 'id') : []"
                        :selected="null"
                        placeholder="Available resources"
                        x-model="resource_id"
                    ></x-forms.select>
                    @error('resource_id')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <x-forms.button>Book</x-forms.button>
                </div>
            </form>
        </div>
    </div>
</div>
