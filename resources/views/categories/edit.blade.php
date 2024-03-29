@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading title="Edit category" />

        <section>
            <form
                action="{{ route('categories.update', [$category->id]) }}"
                method="post"
                class="space-y-8 divide-y divide-gray-200"
            >
                @method('put')
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
                                    value="{{ old('name') ?? $category->name }}"
                                    placeholder="Name"
                                    :hasErrors="$errors->has('name')"
                                />

                                @error('name')
                                    <x-forms.error :message="$message" />
                                @enderror
                            </div>

                            <div class="sm:col-span-4">
                                <x-forms.label for="parent_id">
                                    Parent
                                </x-forms.label>

                                <x-forms.select
                                    id="parent_id"
                                    name="parent_id"
                                    :options="$parentOptions"
                                    :selected="old('parent_id') ?? $category->parent_id"
                                    :hasErrors="$errors->has('parent_id')"
                                    placeholder="Select a parent..."
                                />

                                @error('parent_id')
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
                                    :selected="old('groups') ?? $category->groups->pluck('id')->toArray()"
                                    :hasErrors="$errors->has('groups')"
                                    multiple
                                />

                                @error('groups')
                                    <x-forms.error :message="$message" />
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="pt-5">
                        <div class="flex justify-end">
                            <x-forms.link
                                :href="request()->headers->get('referer') ?? route('categories.index')"
                                dusk="submitcancel"
                            >Cancel</x-forms.link>
                            <x-forms.button
                                dusk="submitupdate"
                            >Update</x-forms.button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </section>
@endsection
