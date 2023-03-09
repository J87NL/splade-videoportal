<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">video
            {{ __('Edit :type', ['type' => __('level')]) }}
        </h2>
    </x-slot>

    <x-splade-modal>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">

                        <x-splade-form
                            action="{{ route('admin.levels.update', ['level' => $level]) }}"
                            method="patch"
                            :default="$level"
                            class="space-y-4"
                        >

                            <x-splade-input name="title" :label="__('Name :type', ['type' => __('level')])" autocomplete="off" />

                            <x-splade-input name="position" :label="__('Order')" autocomplete="off" type="number" min="0" />

                            <x-splade-submit class="btn btn-success">{{ __('Save') }}</x-splade-submit>
                            <button type="button" v-if="modal" @click="modal.close" class="btn btn-info">{{ __('Cancel') }}</button>
                            <Link v-if="!modal" href="{{ route('admin.levels.index') }}" class="btn btn-info">{{ __('Cancel') }}</Link>
                        </x-splade-form>

                        <x-splade-form
                            action="{{ route('admin.levels.destroy', ['level' => $level]) }}"
                            method="delete"
                            confirm
                            class="md:float-right mt-3 md:-mt-10"
                        >
                            <x-splade-submit class="btn btn-danger">{{ __('Delete') }}</x-splade-submit>
                        </x-splade-form>

                    </div>
                </div>
            </div>
        </div>
    </x-splade-modal>
</x-app-layout>
