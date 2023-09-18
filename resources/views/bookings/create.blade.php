@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading title="Create booking" />

        <section>
            <form
                action="{{ route('bookings.store') }}"
                method="post"
                class="space-y-8 divide-y divide-gray-200"
            >
                @csrf

                <div class="space-y-8 divide-y divide-gray-200">
                    <div>
                        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-4">
                                <x-forms.label for="resource_id">
                                    Resource
                                </x-forms.label>

                                <x-forms.select
                                    id="resource_id"
                                    name="resource_id"
                                    :options="$resourceOptions"
                                    :selected="old('resource_id') ?? null"
                                    :hasErrors="$errors->has('resource_id')"
                                />

                                @error('resource_id')
                                    <x-forms.error :message="$message" />
                                @enderror
                            </div>

                            <div class="sm:col-span-4">
                                <x-forms.label for="user_id">
                                    User
                                </x-forms.label>

                                <x-forms.select
                                    id="user_id"
                                    name="user_id"
                                    :options="$userOptions"
                                    :selected="old('user_id') ?? auth()->id()"
                                    :hasErrors="$errors->has('user_id')"
                                />

                                @error('user_id')
                                    <x-forms.error :message="$message" />
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-2">
                                <x-forms.label for="start_time">
                                    Start Time
                                </x-forms.label>

                                <x-forms.input
                                    id="start_time"
                                    name="start_time"
                                    type="text"
                                    value="{{ old('start_time') ?? null }}"
                                    placeholder="Start Time"
                                    :hasErrors="$errors->has('start_time')"
                                />

                                @error('start_time')
                                    <x-forms.error :message="$message" />
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <x-forms.label for="end_time">
                                    End Time
                                </x-forms.label>

                                <x-forms.input
                                    id="end_time"
                                    name="end_time"
                                    type="text"
                                    value="{{ old('end_time') ?? null }}"
                                    placeholder="End Time"
                                    :hasErrors="$errors->has('end_time')"
                                />

                                @error('end_time')
                                    <x-forms.error :message="$message" />
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="pt-5">
                        <div class="flex justify-end">
                            <x-forms.link
                                :href="request()->headers->get('referer') ?? route('bookings.index')"
                                dusk="submitcancel"
                            >Cancel</x-forms.link>
                            <x-forms.button
                                dusk="submitcreate"
                            >Create</x-forms.button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let startTime = document.querySelector('#start_time');
                let endTime = document.querySelector('#end_time');

                flatpickr(startTime, {
                    allowInput: true,
                    enableTime: true,
                    altInput: false,
                    dateFormat: 'Y-m-d H:i',
                    time_24hr: true,
                });

                flatpickr(endTime, {
                    allowInput: true,
                    enableTime: true,
                    altInput: false,
                    dateFormat: 'Y-m-d H:i',
                    time_24hr: true,
                });
            });
        </script>
    @endpush
@endsection
