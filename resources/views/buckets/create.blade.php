@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading title="Create bucket" />

        <section>
            <form
                action="{{ route('buckets.store') }}"
                method="post"
                class="space-y-8 divide-y divide-gray-200"
            >
                @csrf

                <div class="space-y-8 divide-y divide-gray-200">
                    <div>
                        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-4">
                                <x-forms.label for="name">
                                    Name
                                </x-forms.label>

                                <x-forms.input
                                    id="name"
                                    name="name"
                                    value="{{ old('name') ?? null }}"
                                    placeholder="Name"
                                    :hasErrors="$errors->has('name')"
                                />

                                @error('name')
                                    <x-forms.error :message="$message" />
                                @enderror
                            </div>

                            <div class="sm:col-span-4">
                                <x-forms.label for="resources">
                                    Resources
                                </x-forms.label>

                                <x-forms.select
                                    id="resources"
                                    name="resources[]"
                                    :options="$resourceOptions"
                                    :selected="old('resources') ?? null"
                                    :hasErrors="$errors->has('resources')"
                                    multiple
                                />

                                @error('resources')
                                    <x-forms.error :message="$message" />
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="pt-5">
                        <div class="flex justify-end">
                            <x-forms.link
                                :href="request()->headers->get('referer') ?? route('buckets.index')"
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
@endsection
