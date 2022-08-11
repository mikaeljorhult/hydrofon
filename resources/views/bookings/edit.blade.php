@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading title="Edit booking" />

        <section>
            <form
                action="{{ route('bookings.update', [$booking->id]) }}"
                method="post"
                class="space-y-8 divide-y divide-gray-200"
            >
                @method('put')
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
                                    :options="\App\Models\Resource::orderBy('name')->pluck('name', 'id')"
                                    :selected="old('resource_id') ?? $booking->resource_id"
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
                                    :options="\App\Models\User::orderBy('name')->pluck('name', 'id')"
                                    :selected="old('user_id') ?? $booking->user_id"
                                    :hasErrors="$errors->has('user_id')"
                                />

                                @error('user_id')
                                    <x-forms.error :message="$message" />
                                @enderror
                            </div>

                            <div class="sm:col-span-3">
                                <x-forms.label for="start_time">
                                    Start Time
                                </x-forms.label>

                                <x-forms.input
                                    id="start_time"
                                    name="start_time"
                                    type="datetime-local"
                                    value="{{ old('start_time') ?? $booking->start_time }}"
                                    placeholder="Start Time"
                                    :hasErrors="$errors->has('start_time')"
                                />

                                @error('start_time')
                                    <x-forms.error :message="$message" />
                                @enderror
                            </div>

                            <div class="sm:col-span-3">
                                <x-forms.label for="end_time">
                                    End Time
                                </x-forms.label>

                                <x-forms.input
                                    id="end_time"
                                    name="end_time"
                                    type="datetime-local"
                                    value="{{ old('end_time') ?? $booking->end_time }}"
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
                            <x-forms.link :href="session()->get('index-referer-url') ?? request()->headers->get('referer')">
                                Cancel
                            </x-forms.link>
                            <x-forms.button>
                                Update
                            </x-forms.button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </section>
@endsection
