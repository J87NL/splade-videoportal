<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update user') }}
        </h2>
    </x-slot>

    <x-splade-modal>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">

                        <x-auth-validation-errors class="mb-4" />

                        <x-splade-form action="{{ route('admin.users.update', ['user' => $user]) }}"
                                       class="space-y-4"
                                       method="patch"
                                       :default="$user">

                            <x-splade-input id="name" type="text" name="name" :label="__('Name')" required autofocus />
                            <x-splade-input id="email" type="email" name="email" :label="__('Email')" required />
                            <x-splade-select id="is_admin" name="is_admin" :label="__('Is admin')" :options="[0 => __('No'), 1 => __('Yes')]" required />
                            <x-splade-input id="password" type="password" name="password" :label="__('Password')" autocomplete="new-password" />
                            <x-splade-input id="password_confirmation" type="password" name="password_confirmation" :label="__('Confirm Password')" />
                            <x-splade-input id="sportivity_customer_id" type="number" name="sportivity_customer_id" :label="__('Sportivity customer id')" readonly disabled />

                            <x-splade-submit class="btn btn-success">{{ __('Save') }}</x-splade-submit>

                            <button type="button" v-if="modal" @click="modal.close" class="btn btn-info">{{ __('Cancel') }}</button>
                            <Link v-if="!modal" href="{{ route('admin.users.index') }}" class="btn btn-info">{{ __('Cancel') }}</Link>
                        </x-splade-form>

                        <x-splade-form
                            action="{{ route('admin.users.destroy', ['user' => $user]) }}"
                            method="delete"
                            confirm
                            class="md:float-right mt-3 md:-mt-10"
                        >
                            <x-splade-submit class="btn btn-danger">{{ __('Delete') }}</x-splade-submit>
                        </x-splade-form>

                        @if(!empty($user->sportivity_customer_id))
                            <div class="pt-5 text-center">
                                <Link modal href="/admin/artisan/sportivity:update-customer?customer_id={{ $user->sportivity_customer_id }}" class="btn btn-secondary pt-5">
                                    Update Sportivity klantgegevens
                                </Link>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-splade-modal>
</x-app-layout>
