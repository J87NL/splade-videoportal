<div class="min-h-screen bg-gray-100 flex flex-col h-screen">
    @include('layouts.navigation')

    @if(isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif

    <main class="flex-grow bg-gray-100">
        {{ $slot }}
    </main>

    <x-footer />
</div>
