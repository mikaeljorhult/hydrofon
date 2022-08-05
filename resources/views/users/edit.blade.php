@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading :title="'Edit user'" />

        <section>
            <form
                action="{{ route('users.update', [$user->id]) }}"
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
                                    value="{{ old('name') ?? $user->name }}"
                                    placeholder="Name"
                                    :hasErrors="$errors->has('name')"
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
                                    value="{{ old('email') ?? $user->email }}"
                                    placeholder="E-mail"
                                    :hasErrors="$errors->has('email')"
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
                                    :hasErrors="$errors->has('password')"
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
                                    :hasErrors="$errors->has('password_confirmation')"
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
                                    :selected="old('groups') ?? $user->groups->pluck('id')->toArray()"
                                    :hasErrors="$errors->has('groups')"
                                    multiple
                                />
                            </div>

                            <div class="sm:col-span-4">
                                <x-forms.checkbox
                                    id="is_admin"
                                    name="is_admin"
                                    :checked="old('is_admin') ?? $user->is_admin"
                                    :disabled="$user->is(auth()->user())"
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
                                Update
                            </x-forms.button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </section>
@endsection
