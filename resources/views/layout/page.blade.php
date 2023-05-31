@extends('layout.base')

@section('body')
    <div id="app" x-data="{ sidebar_is_open: true }" data-role="layout-page" class="flex w-full h-screen overflow-hidden">
        <aside class="flex flex-col h-full overflow-hidden transition-all border-r-2 border-b-gray-200"
            x-bind:class="sidebar_is_open ? 'w-80' : 'w-0'">
            <h1 id="logo"
                class="flex items-center justify-center w-full h-20 text-2xl font-extrabold tracking-widest cursor-default select-none">
                Taskly.
            </h1>

            <div id="menu"
                class="flex flex-col items-center justify-start flex-grow w-full overflow-x-hidden overflow-y-auto">

                <a data-role="menu-item" href="{{ route("setting") }}"
                    class="flex items-center justify-start w-full gap-3 px-6 py-2 text-sm text-black cursor-pointer select-none {{ Route::currentRouteName() == 'setting' ? 'bg-gray-200' : 'hover:bg-black hover:text-white' }}">
                    <x-fas-gear class="w-6 h-6"/>
                    <p class="text-lg font-normal"> Setting </p>
                </a>

                <a data-role="menu-item" href="{{ route("home") }}"
                    class="flex items-center justify-start w-full gap-3 px-6 py-2 text-sm text-black cursor-pointer select-none {{ Route::currentRouteName() == 'home' ? 'bg-gray-200' : 'hover:bg-black hover:text-white' }}">
                    <x-fas-cube class="w-6 h-6"/>
                    <p class="text-lg font-normal"> Team </p>
                </a>


            </div>
        </aside>

        <div class="flex-col items-center content-center flex-1">
            <header data-role="app-header" class="flex items-center justify-between w-full h-20 px-6 shadow">
                <div id="sidebar-button" class="w-6 h-6" x-on:click="sidebar_is_open = !sidebar_is_open">
                    <template x-if="sidebar_is_open">
                        <x-fas-square-caret-left />
                    </template>

                    <template x-if="!sidebar_is_open">
                        <x-fas-square-caret-right />
                    </template>
                </div>


                <div class="flex items-center justify-center gap-2">
                    <p> <span class="font-bold ">Hello, </span> {{ Auth::user()->name }}</p>
                    <x-avatar :user="Auth::user()" class="w-12 h-12"/>
                </div>
            </header>
            @yield('content')
        </div>
    </div>
@endsection

@pushOnce('component')
    <x-server-request-script/>
@endPushOnce
