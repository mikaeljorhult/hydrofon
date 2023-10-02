@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading title="Create resource" />

        <section>
            <form
                action="{{ route('resources.store') }}"
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
                                <x-forms.label for="description">
                                    Description
                                </x-forms.label>

                                <x-forms.textarea
                                    id="description"
                                    name="description"
                                    placeholder="Description"
                                >{{ old('description') ?? null }}</x-forms.textarea>

                                @error('description')
                                    <x-forms.error :message="$message" />
                                @enderror
                            </div>

                            <div class="sm:col-span-4">
                                <x-forms.label for="categories">
                                    Categories
                                </x-forms.label>

                                <x-forms.select
                                    id="categories"
                                    name="categories[]"
                                    :options="$categoryOptions"
                                    :selected="old('categories') ?? null"
                                    :hasErrors="$errors->has('categories')"
                                    multiple
                                />

                                @error('categories')
                                    <x-forms.error :message="$message" />
                                @enderror
                            </div>

                            <div class="sm:col-span-4">
                                <x-forms.label for="groups">
                                    Groups
                                </x-forms.label>

                                <x-forms.select
                                    id="groups"
                                    name="groups[]"
                                    :options="$groupOptions"
                                    :selected="old('groups') ?? null"
                                    :hasErrors="$errors->has('groups')"
                                    multiple
                                />

                                @error('groups')
                                    <x-forms.error :message="$message" />
                                @enderror
                            </div>

                            <div class="sm:col-span-4">
                                <x-forms.checkbox
                                    id="is_facility"
                                    name="is_facility"
                                    :checked="old('is_facility')"
                                />

                                <x-forms.label for="is_facility" class="ml-1">
                                    Facility
                                </x-forms.label>

                                @error('is_facility')
                                    <x-forms.error :message="$message" />
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="pt-5">
                        <div class="flex justify-end">
                            <x-forms.link
                                :href="request()->headers->get('referer') ?? route('resources.index')"
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
