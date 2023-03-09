<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <x-splade-modal max-width="7xl">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">

                        <Link modal href="{{ route('admin.users.create') }}" class="btn btn-primary">{{ __('New') }}</Link>

                        <x-splade-table :for="$users" striped class="py-6">
                            @cell('is_admin', $user)
                                {{ $user->is_admin ? __('Yes') : __('No') }}
                            @endcell
                            @cell('action', $user)
                                {{ __('Edit') }}
                            @endcell
                        </x-splade-table>

                    </div>
                </div>
            </div>
        </div>
    </x-splade-modal>
</x-app-layout>
