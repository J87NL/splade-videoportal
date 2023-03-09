<section class="h-full md:h-screen gradient-form bg-gray-200">
    <div class="container py-12 px-6 mx-auto">
        <div class="flex justify-center items-center flex-wrap g-6 text-gray-800">
            <div class="xl:w-10/12">
                <div class="block bg-white shadow-lg rounded-lg">
                    <div class="lg:flex lg:flex-wrap g-0">
                        <div class="lg:w-6/12 px-4 md:px-0">
                            <div class="md:p-12 md:mx-6">
                                <div class="pt-5">
                                    <x-application-logo class="block h-10 w-auto fill-current text-gray-600" />
                                </div>
                                {{ $slot }}
                            </div>
                        </div>
                        <div class="lg:w-6/12 flex items-center lg:rounded-r-lg rounded-b-lg lg:rounded-bl-none"
                            style="background: linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593)">

                            <div class="text-white px-4 py-6 md:mx-6 w-full">
                                <h4 class="text-xl font-semibold mb-6">@setting('login.heading')</h4>
                                @setting('login.text')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
