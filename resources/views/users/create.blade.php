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
                                    :hasErrors="$errors->has('name')"
                                />

                                @error('name')
                                    <x-forms.error :message="$message" />
                                @enderror
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
                                    :hasErrors="$errors->has('email')"
                                />

                                @error('email')
                                    <x-forms.error :message="$message" />
                                @enderror
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
                                    :hasErrors="$errors->has('password')"
                                />

                                @error('password')
                                    <x-forms.error :message="$message" />
                                @enderror
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
                                    :hasErrors="$errors->has('password_confirmation')"
                                />

                                @error('password_confirmation')
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
                                    id="is_admin"
                                    name="is_admin"
                                    :checked="old('is_admin')"
                                />

                                <x-forms.label for="is_admin" class="ml-1">
                                    Administrator
                                </x-forms.label>

                                @error('is_admin')
                                    <x-forms.error :message="$message" />
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="pt-5">
                        <div class="flex justify-end">
                            <x-forms.link :href="request()->headers->get('referer') ?? route('users.index')">
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
