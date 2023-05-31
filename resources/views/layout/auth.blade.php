@extends('layout.base')

@pushOnce('head')
@endPushOnce

@section('body')
    <div class="flex items-center content-center w-screen h-screen">

        <div class="items-center content-center hidden w-1/2 h-full none bg-pattern-zig-zag lg:flex">

            <div class="flex flex-col items-start justify-center gap-4 p-6 m-auto bg-white shadow-2xl">
                <h1 class="font-bold text-center text-9xl text-red">Taskly. </h1>
                <p class="mt-4 ml-6 text-lg font-regullar"> From <span class="font-bold">To-Do</span> to <span
                        class="font-bold">Done</span> </p>
            </div>

        </div>

        <div class="flex items-center content-center flex-1 h-full bg-pattern-zig-zag lg:bg-none">
            @yield('form')
        </div>

    </div>
@endsection
