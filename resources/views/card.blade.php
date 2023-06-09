@extends('layout.page')

@section('app-header')
    <h1 class="text-xl font-bold">Card</h1>
@endsection

@section('app-side')


@section('app-side')
<div class="flex flex-col gap-6 px-8 pl-4 mt-2">
    <a class="w-full p-2 border-2 border-gray-200 cursor-pointer select-none rounded-xl"
        href="#">
        <div class="flex items-center w-full gap-2">
            <div class="w-16 h-16 rounded-2xl bg-pattern-sunkist"></div>
            <article class="flex flex-col gap-2 text-sm">
                <h2 class="font-bold">{{ $board->name }}</h2>
                <p class="text-sm line-clamp-3">
                    {{ $team->description }}
                </p>
            </article>
        </div>
    </a>

    @if (Auth::user()->id == $owner->id)
        <section class="w-full overflow-hidden border-2 border-gray-200 cursor-pointer select-none rounded-xl">
            <div data-role="menu-item" onclick="ModalView.show('updateBoard')"
                class="flex items-center w-full gap-3 px-6 py-2 text-black cursor-pointer select-none hover:bg-black hover:text-white">
                <x-fas-pen class="w-4 h-4" />
                <p> Edit </p>
            </div>
            <hr class="w-full border">
            <div data-role="menu-item" onclick="ModalView.show('deleteBoard')"
                class="flex items-center w-full gap-3 px-6 py-2 text-red-600 cursor-pointer select-none hover:bg-black hover:text-white">
                <x-fas-trash class="w-4 h-4" />
                <p>Delete</p>
            </div>
        </section>
    @endif
</div>
@endsection

@section('content')
    <h1>Hi dari card</h1>
@endsection

@pushOnce('page')
    <script>
    </script>
@endPushOnce
