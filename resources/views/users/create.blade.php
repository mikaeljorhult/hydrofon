@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading :title="'Create user'" />

        <section>
            <form
                action="{{ route('users.store') }}"
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
                                />
                            </div>

                            <div class="sm:col-span-4">
                                <x-forms.label for="email">
                                    E-mail
                                </x-forms.label>

                                <x-forms.input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') ?? null }}"
                                    placeholder="E-mail"
                                />
                            </div>

                            <div class="sm:col-span-4">
                                <x-forms.label for="password">
                                    Password
                                </x-forms.label>

                                <x-forms.input
                                    id="password"
                                    name="password"
                                    type="password"
                                    value=""
                                    placeholder="Password"
                                />
                            </div>

                            <div class="sm:col-span-4">
                                <x-forms.label for="password_confirmation">
                                    Confirm Password
                                </x-forms.label>

                                <x-forms.input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    value=""
                                    placeholder="Confirm Password"
                                />
                            </div>

                            <div class="sm:col-span-4">
                                <x-forms.label for="groups">
                                    Groups
                                </x-forms.label>

                                <x-forms.select
                                    id="groups"
                                    name="groups[]"
                                    :options="\App\Models\Group::orderBy('name')->pluck('name', 'id')"
                                    :selected="old('groups') ?? null"
                                    multiple
                                />
                            </div>

                            <div class="sm:col-span-4">
                                <x-forms.checkbox
                                    id="is_admin"
                                    name="is_admin"
                                    :checked="old('is_admin')"
                                />

                                <x-forms.label for="is_admin" class="ml-1">
                                    Administrator
                                </x-forms.label>
                            </div>
                        </div>
                    </div>

                    <div class="pt-5">
                        <div class="flex justify-end">
                            <x-forms.link :href="session()->get('index-referer-url') ?? request()->headers->get('referer')">
                                Cancel
                            </x-forms.link>
                            <x-forms.button>
                                Create
                            </x-forms.button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </section>
@endsection
