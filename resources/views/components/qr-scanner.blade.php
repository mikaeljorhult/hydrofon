<div
    class="mx-1 relative"
    x-data="qrScanner()"
    x-on:click.outside="outsideClick"
>
    <div>
        <button
            type="button"
            class="group relative bg-white p-1 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-300"
            id="qrscanner-menu-button"
            aria-expanded="false"
            aria-haspopup="true"
            x-bind:class="{'text-green-600': isScanning, 'hover:text-green-700': isScanning, 'text-gray-400': !isScanning, 'hover:text-gray-500': !isScanning}"
            x-on:click.prevent="isOpen = !isOpen"
        >
            <span class="sr-only">Open QR Scanner</span>
            <x-heroicon-o-qrcode class="w-6 h-6" />
        </button>
    </div>

    <div
        class="origin-top-right absolute right-0 mt-2 w-72 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
        role="menu"
        aria-orientation="vertical"
        aria-labelledby="qrscanner-menu-button"
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
            <div
                class="group w-full h-36 relative bg-gray-100"
                x-show="isScanning"
            >
                <video x-ref="video"></video>
                <button
                    class="w-full absolute inset-0 bg-gray-100/75 text-sm font-light hidden group-hover:block"
                    x-on:click="stop"
                >Stop scanning</button>
            </div>

            <button
                class="block w-full h-36 bg-gray-100 text-sm font-light"
                x-show="!isScanning && hasCamera"
                x-on:click="start"
            >Start scanning</button>

            <div
                class="flex justify-center items-center w-full h-36 text-sm font-light"
                x-show="!hasCamera"
            >No camera available</div>
        </div>
    </div>
</div>
