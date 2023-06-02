@extends('layout.page')

@section('content')

    <div class="flex flex-col w-full h-full gap-10 px-8 py-6 overflow-auto">
        <header class="w-full">
            <form class="flex items-center gap-4" id="search-form" action="{{ route('searchTeam') }}" method="POST">
                @csrf
                <x-form.text icon="fas-cube" name="team_name" placeholder="Team's name" value="{{ old('value') }}"/>
                <div class="h-full min-w-min">
                    <x-form.button type="submit" primary class="h-full">
                        <x-fas-magnifying-glass class="w-4 h-4" />Search
                    </x-form.button>
                </div>

            </form>
        </header>
        <section class="flex flex-col gap-6">
            <header>
                <h2 class="ml-6 text-3xl font-bold">My Teams</h2>
            </header>

            <hr>

            @if (!$invites->isEmpty())
                <div class="flex flex-wrap gap-x-8 gap-y-6">
                    <div
                        class="flex flex-col items-center justify-center gap-2 text-gray-400 transition duration-300 bg-gray-100 shadow-md w-72 h-52 rounded-xl hover:shadow-2xl">
                        <x-fas-plus class="w-8 h-8" />
                        <p>Create Team</p>
                    </div>

                    @foreach ($invites as $team)
                        <div
                            class="flex flex-col transition duration-300 border border-gray-200 shadow-xl rounded-xl h-52 w-72 hover:shadow-2xl bg-pattern-{{ $team->pattern }} overflow-hidden">
                            <div class="flex-grow w-full "></div>
                            <article class="flex flex-col w-full h-20 gap-1 px-4 py-2 bg-white">
                                <h3 class="overflow-hidden font-semibold truncate text-bold">{{ $team->name }}</h3>
                                <p class="flex-grow w-full text-xs break-all line-clamp-2 text-ellipsis max-h-8 ">
                                    {{ $team->description }} </p>
                            </article>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="flex flex-wrap gap-x-8 gap-y-6">
                <div
                    class="flex flex-col items-center justify-center gap-2 text-gray-400 transition duration-300 bg-gray-100 shadow-md w-72 h-52 rounded-xl hover:shadow-2xl">
                    <x-fas-plus class="w-8 h-8" />
                    <p>Create Team</p>
                </div>

                @foreach ($teams as $team)
                    <div
                        class="flex flex-col transition duration-300 border border-gray-200 shadow-xl rounded-xl h-52 w-72 hover:shadow-2xl bg-pattern-{{ $team->pattern }} overflow-hidden">
                        <div class="flex-grow w-full "></div>
                        <article class="flex flex-col w-full h-20 gap-1 px-4 py-2 bg-white">
                            <h3 class="overflow-hidden font-semibold truncate text-bold">{{ $team->name }}</h3>
                            <p class="flex-grow w-full text-xs break-all line-clamp-2 text-ellipsis max-h-8 ">
                                {{ $team->description }} </p>
                        </article>
                    </div>
                @endforeach
            </div>
        </section>

    </div>
@endsection

@pushOnce('page')
    <script>
        const searchForm = document.getElementById("search-form");
        console.log("previous: {{ old('team_name') }}");
    </script>
@endPushOnce
