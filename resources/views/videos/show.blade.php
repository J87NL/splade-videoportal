<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $video->title }}
        </h2>
    </x-slot>

    <x-splade-modal max-width="7xl">
        <div class="py-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <h2 class="font-medium leading-tight text-2xl mt-0 mb-3">{{ $video->title }}</h2>

                    <VideoPlayer
                        src="{{ $video->video_src }}"
                        type="{{ $video->video_type }}"
                        poster="{{ $video->getLastMediaUrl('images') }}"
                        slug="{{ $video->slug }}"
                    />
                </div>
            </div>
        </div>
    </x-splade-modal>
</x-app-layout>
