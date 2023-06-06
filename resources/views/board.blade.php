@extends('layout.page')

@section('app-header')
    <div class="flex items-center gap-2">
        <h1 class="text-xl font-bold">Board: </h1>
        <p class="text-xl">{{ $board->name }}</p>
    </div>
@endsection


@section('app-side')
    <div class="flex flex-col gap-4 px-4">
        <a class="w-full p-2 border-2 border-gray-200 cursor-pointer select-none rounded-xl"
            href="{{ route('viewTeam', ['team_id' => $team->id]) }}">
            <div class="flex items-center w-full gap-2">
                <div class="w-16 h-16">
                    <x-avatar name="{{ $team->name }}" asset="{{ $team->image_path }}"
                        class="!w-16 !h-16 !aspect-square !text-xl" />
                </div>
                <article class="flex flex-col gap-2 text-sm">
                    <h2 class="font-bold">{{ $team->name }}</h2>
                    <p class="text-sm line-clamp-3">
                        {{ $team->description }}
                    </p>
                </article>
            </div>
        </a>

        <section class="flex flex-col w-full gap-4 ">
            @if (Auth::user()->id == $owner->id)
                <x-form.button outline type="button" action="ModalView.show('updateBoard')"
                    class="!border-2 !text-sm h-min !px-4">
                    <x-fas-pen class="w-4 h-4" />
                    Edit
                </x-form.button>
            @endif
        </section>

    </div>
@endsection


@section('content')
    <div class="w-full h-full min-h-full overflow-hidden overflow-x-scroll bg-grad-{{ $board->pattern }}">
        <section class="flex h-full min-w-full gap-4 p-4">
            {{-- Column --}}
            @for ($i = 1; $i <= 1; $i++)
                <x-column id="{{ $i }}" name="kolom ke-{{ $i }}"></x-column>
            @endfor
        </section>
    </div>
@endsection

@pushOnce('page')
    <script>
        @if ($errors->any())
            ToastView.notif("Warning", "{{ $errors->first() }}");
        @endif
    </script>
@endPushOnce
