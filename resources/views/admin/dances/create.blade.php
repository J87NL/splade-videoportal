<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add a new dancetype') }}
        </h2>
    </x-slot>

    <x-splade-modal>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">

                        <x-splade-form action="{{ route('admin.dances.store') }}" class="space-y-4">

                            <x-splade-input name="title" :label="__('Name :type', ['type' => __('dance')])" autocomplete="off" />
                            <x-splade-select name="dancetype_id" :label="__('Dancetype')" :options="$dancetypes" choices />
                            <x-splade-input name="position" :label="__('Order')" autocomplete="off" type="number" min="0" />

                            <x-splade-submit>{{ __('Save') }}</x-splade-submit>
                        </x-splade-form>

                    </div>
                </div>
            </div>
        </div>
    </x-splade-modal>
</x-app-layout>
