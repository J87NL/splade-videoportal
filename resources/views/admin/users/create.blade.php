<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add a new user') }}
        </h2>
    </x-slot>

    <x-splade-modal>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">

                        <x-auth-validation-errors class="mb-4" />

                        <x-splade-form action="{{ route('admin.users.store') }}" class="space-y-4"
                                       default="{ is_admin: 0 }">
                            <x-splade-input id="name" type="text" name="name" :label="__('Name')" required autofocus />
                            <x-splade-input id="email" type="email" name="email" :label="__('Email')" required />
                            <x-splade-select id="is_admin" name="is_admin" :label="__('Is admin')" :options="[0 => __('No'), 1 => __('Yes')]" required />
                            <x-splade-input id="password" type="password" name="password" :label="__('Password')" required autocomplete="new-password" />
                            <x-splade-input id="password_confirmation" type="password" name="password_confirmation" :label="__('Confirm Password')" required />
                            <x-splade-input id="sportivity_customer_id" type="number" name="sportivity_customer_id" :label="__('Sportivity customer id')" />

                            <x-splade-submit>{{ __('Save') }}</x-splade-submit>
                        </x-splade-form>
                    </div>
                </div>
            </div>
        </div>
    </x-splade-modal>
</x-app-layout>
