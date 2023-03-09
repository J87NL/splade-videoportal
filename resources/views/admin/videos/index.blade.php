<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Videos') }}
        </h2>
    </x-slot>

    <x-splade-modal max-width="7xl">
            <x-splade-toggle>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="m-2 flex items-center sm:hidden">
                            <button @click="toggle" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path v-bind:class="{ hidden: toggled, 'inline-flex': !toggled }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path v-bind:class="{ hidden: !toggled, 'inline-flex': toggled }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span class="ml-5">@setting('videos.switch-button')</span>
                            </button>
                        </div>

                        <div v-bind:class="{ block: toggled, hidden: !toggled }" class="md:block">
                            <ul class="nav nav-tabs flex flex-col md:flex-row flex-wrap list-none border-b-0 px-4 mb-4" id="tabs-tab">
                                @foreach($dances as $dance)
                                    <li class="nav-item flex-grow text-center" role="presentation">
                                        <Link href="{{ route('admin.videos.index', ['filter[dance_id]' => $dance->id]) }}"
                                          @click="toggle"
                                           @class(['nav-link', 'w-full', 'block', 'font-medium', 'text-xs', 'leading-tight',
                                                'uppercase', 'border-x-0', 'border-t-0', 'border-b-2', 'border-transparent', 'px-6',
                                                'py-3', 'my-2', 'hover:border-transparent', 'hover:bg-gray-100', 'focus:border-transparent',
                                                'active' => (app('request')->input('filter.dance_id') == $dance->id)
                                            ])>

                                            {{ $dance->title }}
                                        </Link>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="p-6 bg-white border-b border-gray-200">

                            <Link modal href="{{ route('admin.videos.create') }}" class="btn btn-primary">{{ __('New') }}</Link>

                            <x-splade-table :for="$videos" striped class="py-6">
                                @cell('action', $video)
                                    {{ __('Edit') }}
                                @endcell
                            </x-splade-table>

                        </div>
                    </div>
                </div>
            </div>
        </x-splade-toggle>
    </x-splade-modal>
</x-app-layout>
