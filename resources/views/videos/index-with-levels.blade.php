<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="flex items-start">
                        <ul class="nav nav-tabs flex flex-col flex-wrap list-none border-b-0 pl-0 mr-4 mt-55px" id="tabs-tabVertical" role="tablist">
                            @foreach($levels as $level)

                                <li class="nav-item flex-grow text-center" role="presentation">
                                    <a href="#{{ $level->slug }}" @class(['nav-link', 'block', 'font-medium',
                                        'text-xs', 'leading-tight', 'uppercase', 'border-x-0', 'border-t-0', 'border-b-2',
                                        'border-transparent', 'px-6', 'py-3', 'my-2', 'hover:border-transparent',
                                        'hover:bg-gray-100', 'focus:border-transparent', 'active' => $loop->first])
                                        id="tabs-home-tabVertical" data-bs-toggle="pill" data-bs-target="#{{ $level->slug }}"
                                        role="tab" aria-controls="{{ $level->slug }}" aria-selected="true">

                                        {{ $level->title }}
                                    </a>
                                </li>

                            @endforeach
                        </ul>
                        <div class="tab-content" id="tabs-tabContentVertical">
                            @foreach($levels as $level)
                                <div @class(['tab-pane', 'fade', 'show', 'active' => $loop->first]) role="tabpanel"
                                     id="{{ $level->slug }}" aria-labelledby="{{ $level->slug }}-tabVertical">

                                    <ul class="nav nav-tabs flex flex-col md:flex-row flex-wrap list-none border-b-0 pl-0 mb-4" id="tabs-tab" role="tablist">
                                        @foreach($dances as $dance)
                                            <li class="nav-item" role="presentation">
                                                <a href="#{{ $level->slug }}-{{ $dance->slug }}"
                                                   @class(['nav-link', 'block', 'font-medium', 'text-xs', 'leading-tight',
                                                        'uppercase', 'border-x-0', 'border-t-0', 'border-b-2', 'border-transparent', 'px-6',
                                                        'py-3', 'my-2', 'hover:border-transparent', 'hover:bg-gray-100', 'focus:border-transparent',
                                                        'active' => $loop->first
                                                    ])
                                                    id="{{ $level->slug }}-{{ $dance->slug }}-tab"
                                                    data-bs-toggle="pill"
                                                    data-bs-target="#{{ $level->slug }}-{{ $dance->slug }}"
                                                    role="tab"
                                                    aria-controls="{{ $level->slug }}-{{ $dance->slug }}"
                                                    aria-selected="true">

                                                    {{ $dance->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div class="tab-content" id="tabs-tabContent">
                                        @foreach($dances as $dance)
                                            <div @class(['tab-pane', 'fade', 'show', 'active' => $loop->first])
                                                 id="{{ $level->slug }}-{{ $dance->slug }}"
                                                 role="tabpanel"
                                                 aria-labelledby="{{ $level->slug }}-{{ $dance->slug }}-tab">

                                                <div class="inline-grid grid-cols-3 gap-3 place-content-center">
                                                    @foreach($dance->videos->filter(fn ($video) => $video->levels->contains('id', $level->id)) as $video)
                                                        <Link modal href="{{ route('videos.show', $video) }}" class="rounded-lg shadow-lg bg-white max-w-sm text-center">
                                                            @if(!empty($video->getLastMedia('images')))
                                                                <img src="{{ $video->getLastMediaUrl('images') }}" alt="{{ $video->title }}" class="rounded-t-lg min-h-[200px] w100" />
                                                            @else
                                                                <p class="min-h-[200px] min-w-[311px]"></p>
                                                            @endif
                                                            <div class="p-6">
                                                                <h5 class="text-gray-900 text-xl font-medium mb-2">
                                                                    {{ $video->title }}
                                                                </h5>
                                                            </div>
                                                        </Link>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
