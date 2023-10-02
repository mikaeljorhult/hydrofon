@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading title="Create identifier" />

        <section>
            <form
                action="{{ route($identifiable->getTable().'.identifiers.store', $identifiable) }}"
                method="post"
                class="space-y-8 divide-y divide-gray-200"
            >
                @csrf

                <div class="space-y-8 divide-y divide-gray-200">
                    <div>
                        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-4">
                                <x-forms.label for="value">
                                    Identifier
                                </x-forms.label>

                                <x-forms.input
                                    id="value"
                                    name="value"
                                    value="{{ old('value') ?? null }}"
                                    placeholder="Value"
                                    :hasErrors="$errors->has('value')"
                                />

                                @error('value')
                                    <x-forms.error :message="$message" />
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="pt-5">
                        <div class="flex justify-end">
                            <x-forms.link
                                :href="request()->headers->get('referer') ?? route($identifiable->getTable().'.identifiers.index', $identifiable)"
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
