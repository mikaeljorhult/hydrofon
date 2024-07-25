@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <section class="container space-y-8">
        <x-heading title="Settings" :url="route('settings.index')" />

        <form action="{{ route('settings.update') }}" method="post">
            @csrf

            <div class="mb-4">
                <h2>Approval</h2>

                <div class="max-w-xl">
                    <p class="mb-2">
                        The system can require bookings to be approved before equipment can be retrieved at the desk.
                        Bookings already made will not be affected by changes to the setting.
                    </p>

                    <p>
                        <x-forms.select
                            name="require_approval"
                            :options="\App\Enums\ApprovalSetting::options()"
                            :selected="$require_approval"
                            :hasErrors="$errors->has('require_approval')"
                        />
                    </p>
                </div>
            </div>

            <div class="mb-4">
                <h2>Desk inclusion</h2>

                <div class="max-w-xl">
                    <p class="mb-2">
                        Bookings that have started but not yet ended are displayed within the desk view by default.
                        This timeframe can be extended 240 minutes earlier or later to allow bookings to be checked
                        out even outside the reserved time if available.
                    </p>

                    <p>
                        <x-forms.label
                            for="desk_inclusion_earlier"
                        >Earlier</x-forms.label>

                        <x-forms.input
                            id="desk_inclusion_earlier"
                            name="desk_inclusion_earlier"
                            type="number"
                            min="0"
                            max="240"
                            :value="$desk_inclusion_earlier"
                            :hasErrors="$errors->has('desk_inclusion_earlier')"
                        />
                    </p>

                    <p>
                        <x-forms.label
                            for="desk_inclusion_later"
                        >Later</x-forms.label>

                        <x-forms.input
                            id="desk_inclusion_later"
                            name="desk_inclusion_later"
                            type="number"
                            min="0"
                            max="240"
                            :value="$desk_inclusion_later"
                            :hasErrors="$errors->has('desk_inclusion_later')"
                        />
                    </p>
                </div>
            </div>

            <div class="mb-4">
                <h2>Prune older content</h2>

                <div class="max-w-xl">
                    <p class="mb-2">
                        Users and bookings can be automatically removed after a number of days. Be default, bookings
                        are removed six months (180 days) after after they've ended if they're not still checked out. Users
                        are removed one year (365 days) after their last login.
                    </p>

                    <p>
                        <x-forms.label
                            for="prune_bookings"
                        >Bookings</x-forms.label>

                        <x-forms.input
                            id="prune_bookings"
                            name="prune_bookings"
                            type="number"
                            min="0"
                            max="3650"
                            :value="$prune_bookings"
                            :hasErrors="$errors->has('prune_bookings')"
                        />
                    </p>

                    <p>
                        <x-forms.label
                            for="prune_users"
                        >Users</x-forms.label>

                        <x-forms.input
                            id="prune_users"
                            name="prune_users"
                            type="number"
                            min="0"
                            max="3650"
                            :value="$prune_users"
                            :hasErrors="$errors->has('prune_users')"
                        />
                    </p>
                </div>
            </div>

            <div>
                <div class="max-w-xl">
                    <p class="mt-4">
                        <x-forms.button>
                            Save settings
                        </x-forms.button>
                    </p>
                </div>
            </div>
        </form>
    </section>
@endsection
