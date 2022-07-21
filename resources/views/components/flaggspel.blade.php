<div
    aria-live="assertive" class="fixed inset-0 z-50 flex items-end px-4 py-6 pointer-events-none sm:p-6"
    x-data="flaggspel()"
    x-bind="base"
>
    <div class="w-full flex flex-col items-center space-y-4 sm:items-end">
        <template x-for="(message, index) in messages" :key="index" hidden>
            <div
                class="max-w-sm w-full bg-white shadow-md rounded pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
                x-transition:enter="transition transform ease-out duration-300"
                x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                x-show="message.visible"
            >
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <x-heroicon-o-check-circle class="h-6 w-6 text-green-400" />
                        </div>

                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p
                                class="text-sm font-medium text-gray-900"
                                x-text="message.title"
                            >&nbsp;</p>

                            <p
                                class="mt-1 text-sm text-gray-500"
                                x-show="message.body"
                                x-text="message.body"
                            >&nbsp;</p>
                        </div>

                        <div class="ml-4 flex-shrink-0 flex">
                            <button
                                type="button"
                                class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                x-on:click="hide(message)"
                            >
                                <span class="sr-only">Close</span>
                                <x-heroicon-s-x class="h-6 w-6 fill-current" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
