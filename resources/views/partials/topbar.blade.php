@admin
    <nav class="topbar h-16 flex items-center justify-between px-8 bg-white">
        <section class="topbar-desk w-1/3">
            <form action="{{ route('desk') }}" method="post" class="flex items-center">
                @csrf

                <x-heroicon-s-search class="w-4 flex-shrink-0" />

                <x-forms.label for="search" class="sr-only">
                    Search for resource or user
                </x-forms.label>

                <x-forms.input
                    type="search"
                    name="search"
                    id="search"
                    placeholder="Search resource or user..."
                    value="{{ $search ?? null }}"
                    class="!border-transparent !shadow-none"
                />

                <x-forms.button class="sr-only">
                    Search
                </x-forms.button>
            </form>
        </section>

        <section class="topbar-impersonation w-1/3">
            <form action="{{ route('impersonation') }}" method="post" class="flex items-center">
                @csrf

                <x-heroicon-s-eye class="w-4 flex-shrink-0" />

                <x-forms.label for="user_id" class="sr-only">
                    Select user to impersonate
                </x-forms.label>

                <x-forms.select
                    name="user_id"
                    id="user_id"
                    :options="\App\Models\User::pluck('name', 'id')"
                    :selected="session()->get('impersonate', null)"
                    placeholder="Impersonate user..."
                    class="!border-transparent !shadow-none"
                />

                <x-forms.button class="sr-only">
                    Impersonate
                </x-forms.button>
            </form>
        </section>
    </nav>
@endadmin
