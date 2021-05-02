@admin
    <nav class="topbar flex justify-between py-4 px-8 bg-white">
        <section class="topbar-desk w-1/3">
            {!! Form::open(['route' => 'desk', 'class' => 'flex items-center']) !!}
                <x-heroicon-s-search class="w-4 flex-shrink-0" />

                <label for="search" class="sr-only">
                    Search for resource or user
                </label>

                {!! Form::text('search', $search ?? null, ['class' => 'field mb-0 bg-transparent border-transparent text-sm focus:border-transparent', 'placeholder' => 'Search resource or user...']) !!}
                <x-forms.button class="sr-only">
                    Search
                </x-forms.button>
            {!! Form::close() !!}
        </section>

        <section class="topbar-impersonation w-1/3">
            {!! Form::open(['route' => 'impersonation', 'class' => 'flex items-center']) !!}
                <x-heroicon-s-eye class="w-4 flex-shrink-0" />

                <label for="user_id" class="sr-only">
                    Select user to impersonate
                </label>

                {!! Form::select('user_id', \App\Models\User::pluck('name', 'id'), session()->get('impersonate', null), ['class' => 'field mb-0 bg-transparent border-transparent text-sm focus:border-transparent', 'placeholder' => 'Impersonate user...']) !!}
                <x-forms.button class="sr-only">
                    Impersonate
                </x-forms.button>
            {!! Form::close() !!}
        </section>
    </nav>
@endadmin
