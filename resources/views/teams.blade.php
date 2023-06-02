@extends('layout.page')

@section('content')
    <div class="flex flex-col w-full h-full gap-10 px-8 py-6 ">
        <header class="w-full">
            <form class="flex items-center gap-4" id="search-form">
                <x-form.text icon="fas-cube" name="search" placeholder="Team's name" />
                <div class="h-full min-w-min">
                    <x-form.button class="h-full" primary>
                        <x-fas-magnifying-glass class="w-4 h-4" />Search
                    </x-form.button>
                </div>

            </form>
        </header>
        <section class="flex flex-col gap-4 pl-6">
            <header>
                <h2 class="text-3xl font-bold">My Teams</h2>
            </header>

            <hr>

            <div class="">
                @foreach ($teams as $team)
                <div class="">
                    {{ $team->name }}
                </div>
                @endforeach
            </div>
        </section>

    </div>
@endsection

@pushOnce('page')
    <script>
        const searchForm = document.getElementById("search-form");
        console.log(searchForm);
    </script>
@endPushOnce
