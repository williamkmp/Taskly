@extends('layout.base')

@section('body')
    <div id="app" x-data="{ sidebar_is_open: true }" data-role="layout-page" class="flex w-full h-screen overflow-hidden">
        <aside class="flex flex-col h-full overflow-hidden transition-all border-r-2 border-b-gray-200"
            x-bind:class="sidebar_is_open ? 'w-80' : 'w-0'">
            <h1 id="logo"
                class="flex items-center justify-center w-full h-16 text-2xl font-extrabold tracking-widest cursor-default select-none">
                Taskly.
            </h1>
            <section class="flex flex-col items-center justify-start w-full gap-2 overflow-x-hidden overflow-y-auto">
                <div id="menu" class="flex flex-col items-center justify-start w-full">

                    <a data-role="menu-item" href="{{ route('setting') }}"
                        class="flex items-center justify-start w-full gap-3 px-6 py-2 text-sm text-black cursor-pointer select-none {{ Route::currentRouteName() == 'setting' ? 'bg-gray-200' : 'hover:bg-black hover:text-white' }}">
                        <x-fas-gear class="w-6 h-6" />
                        <p class="text-lg font-normal"> Setting </p>
                    </a>

                    <a data-role="menu-item" href="{{ route('home') }}"
                        class="flex items-center justify-start w-full gap-3 px-6 py-2 text-sm text-black cursor-pointer select-none {{ Route::currentRouteName() == 'home' ? 'bg-gray-200' : 'hover:bg-black hover:text-white' }}">
                        <x-fas-cube class="w-6 h-6" />
                        <p class="text-lg font-normal"> Team </p>
                    </a>
                </div>

                @hasSection('app-side')
                    <div class="flex-grow w-full">
                        @yield('app-side')
                    </div>
                @endif
            </section>
        </aside>

        <div class="flex flex-col items-center content-center flex-1 h-full overflow-y-auto">
            <header data-role="app-header" class="sticky flex items-center justify-between w-full h-16 px-6 shadow">
                <div class="flex items-center gap-4">
                    <div id="sidebar-button" class="w-6 h-6" x-on:click="sidebar_is_open = !sidebar_is_open">
                        <template x-if="sidebar_is_open">
                            <x-fas-square-xmark />
                        </template>

                        <template x-if="!sidebar_is_open">
                            <x-fas-square-poll-horizontal />
                        </template>
                    </div>

                    @yield('app-header')
                </div>


                <div class="flex items-center justify-center gap-2">
                    <p> <span class="font-bold ">Hello, </span> {{ Auth::user()->name }}</p>
                    <x-avatar name="{{ Auth::user()->name }}" asset="{{ Auth::user()->image_path }}" class="w-12 h-12"
                        href="{{ route('setting') }}" />
                </div>
            </header>
            <div class="flex-grow w-full overflow-y-auto">
                @yield('content')
            </div>
        </div>
    </div>
@endsection

@pushOnce('component')
    <x-server-request-script />
@endPushOnce

@pushOnce('page')
    <script>
        document.querySelectorAll("a").forEach(
            link => link.addEventListener("click", () => PageLoader.show())
        );

        document.querySelectorAll("form[action][method]").forEach(
            form => form.addEventListener("submit", () => PageLoader.show())
        );
    </script>
@endPushOnce
