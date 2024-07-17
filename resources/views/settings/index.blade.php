@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <section class="container space-y-8">
        <x-heading title="Settings" :url="route('settings.index')" />

        <form action="{{ route('settings.update') }}" method="post">
            @csrf

            <div>
                <h2>Approval</h2>

                <div class="max-w-xl">
                    <p class="mb-2">
                        The system can require bookings to be approved before equipment can be retrieved at the desk.
                        Bookings already made will not be affected by changes to the setting.
                    </p>

                    <p class="mb-4">
                        <x-forms.select
                            name="require_approval"
                            :options="\App\Enums\ApprovalSetting::options()"
                            :selected="$require_approval"
                            :hasErrors="$errors->has('require_approval')"
                        />
                    </p>

                    <x-forms.button>
                        Save settings
                    </x-forms.button>
                </div>
            </div>
        </form>
    </section>
@endsection
