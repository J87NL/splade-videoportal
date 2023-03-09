<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit :type', ['type' => __('video')]) }}
        </h2>
    </x-slot>

    <x-splade-modal>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">

                        <x-splade-form
                            action="{{ route('admin.videos.update', ['video' => $video]) }}"
                            method="patch"
                            :default="$video"
                            class="space-y-4"
                        >
                            <x-splade-select name="dance_id" :label="__('Dance')" :options="$dances" />
                            <x-splade-input name="title" :label="__('Name :type', ['type' => __('video')])" autocomplete="off" />
                            <x-splade-input name="position" :label="__('Order')" autocomplete="off" type="number" min="0" />
                            <x-splade-select name="levels[]" :label="__('Levels')" :options="$levels" multiple choices relation />
                            <x-splade-input name="url" :label="__('Video URL (YouTube/Vimeo)')" autocomplete="off" />
                            <x-splade-file name="videoPath" :label="__('Or video file')" filepond server accept="video/quicktime, video/mp4" />

                            @if(!empty($video->path))
                                <div class="pt-3">
                                    {{ __('Current video, upload a new one to replace') }}:
                                    <video class="video-js" controls preload="auto" width="480" height="198">
                                        <source src="{{ route('videos.file', $video) }}" type="video/mp4" />
                                        <p class="vjs-no-js">
                                            Schakel JavaScript in om deze video weer te geven of upgrade naar een browser die
                                            <a href="https://videojs.com/html5-video-support/" target="_blank">HTML5 video's ondersteunt</a>
                                        </p>
                                    </video>
                                </div>
                            @endif

                            <x-splade-input name="views_count" :label="__('Viewcount')" readonly="readonly" disabled="disabled" />

                            <x-splade-file name="image" :label="__('Picture')" />

                            @if(!empty($video->getLastMedia('images')))
                                <img src="{{ $video->getLastMediaUrl('images') }}" class="mx-auto" alt="" height="200" />
                            @endif

                            <x-splade-submit class="btn btn-success">{{ __('Save') }}</x-splade-submit>
                            <button type="button" v-if="modal" @click="modal.close" class="btn btn-info">{{ __('Cancel') }}</button>
                            <Link v-if="!modal" href="{{ route('admin.videos.index') }}" class="btn btn-info">{{ __('Cancel') }}</Link>
                        </x-splade-form>

                        <x-splade-form
                            action="{{ route('admin.videos.destroy', ['video' => $video]) }}"
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
