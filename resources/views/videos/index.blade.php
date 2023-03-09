<x-app-layout>
    <x-splade-toggle>
        <div class="p-2 md:py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <!-- Hamburger -->
                    <div class="m-2 flex items-center sm:hidden">
                        <button @click="toggle" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path v-bind:class="{ hidden: toggled, 'inline-flex': !toggled }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path v-bind:class="{ hidden: !toggled, 'inline-flex': toggled }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span class="ml-5">@setting('videos.switch-button')</span>
                        </button>
                    </div>

                    <div class="p-6 bg-white border-b border-gray-200">
                        <div v-bind:class="{ block: toggled, hidden: !toggled }" class="md:block">
                            <ul class="nav nav-tabs flex flex-col md:flex-row flex-wrap list-none border-b-0 pl-0 mb-4" id="tabs-tab" role="tablist">
                                @foreach($dances as $dance)
                                    <li class="nav-item flex-grow text-center" role="presentation">
                                        <a href="#{{ $dance->slug }}"
                                           @class(['nav-link', 'w-full', 'block', 'font-medium', 'text-xs', 'leading-tight',
                                                'uppercase', 'border-x-0', 'border-t-0', 'border-b-2', 'border-transparent', 'px-6',
                                                'py-3', 'my-2', 'hover:border-transparent', 'hover:bg-gray-100', 'focus:border-transparent',
                                                'active' => $loop->first
                                            ])
                                            @click="toggle"
                                            id="{{ $dance->slug }}-tab"
                                            data-bs-toggle="pill"
                                            data-bs-target="#{{ $dance->slug }}"
                                            role="tab"
                                            aria-controls="{{ $dance->slug }}"
                                            aria-selected="{{ $loop->first ? 'true' : 'false' }}">

                                            {{ $dance->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="tab-content" id="tabs-tabContent">
                            @foreach($dances as $dance)
                                <div @class(['tab-pane', 'fade', 'show' => $loop->first, 'active' => $loop->first])
                                     id="{{ $dance->slug }}"
                                     role="tabpanel"
                                     aria-labelledby="{{ $dance->slug }}-tab">

                                    <div class="inline-grid grid-cols-1 md:grid-cols-4 gap-3 place-content-center">
                                        @foreach($dance->videos as $video)
                                            <Link modal href="{{ route('videos.show', $video) }}" class="rounded-lg shadow-lg bg-white max-w-sm text-center">
                                                @if(!empty($video->getLastMedia('images')))
                                                    <img src="{{ $video->getLastMediaUrl('images') }}" alt="{{ $video->title }}" class="rounded-t-lg min-h-[160px] w100" />
                                                @else
                                                    <p class="min-h-[160px] min-w-[249px]"></p>
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
                </div>
            </div>
        </div>
    </x-splade-toggle>
</x-app-layout>
