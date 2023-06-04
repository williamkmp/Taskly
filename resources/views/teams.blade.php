@extends('layout.page')

@section('content')

    <template is-modal="createTeam">
        <div class="flex flex-col w-full gap-4 p-4">
            <h1 class="text-3xl font-bold">Create Team</h1>
            <hr>
            <form action="{{ route('doCreateTeam') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <x-form.text name="team_name" label="Team's Name" required/>
                <x-form.textarea name="team_description" label="Team's Description" required/>
                <x-form.button class="mt-4" type="submit" primary>Submit</x-form.button>
            </form>
        </div>
    </template>

    <div class="flex flex-col w-full h-full gap-6 px-8 py-6 overflow-auto">
        <header class="w-full">
            <form class="flex items-center gap-4" id="search-form" action="{{ route('searchTeam') }}" method="POST">
                @csrf
                <x-form.text icon="fas-cube" name="team_name" placeholder="Team's name" value="{{ session('__old_team_name') }}"/>
                <div class="h-full min-w-min">
                    <x-form.button type="submit" primary class="h-full">
                        <x-fas-magnifying-glass class="w-4 h-4" />Search
                    </x-form.button>
                </div>
            </form>
        </header>

        @if (!$invites->isEmpty())
            <section class="flex flex-col gap-6">
                <header>
                    <h2 class="ml-6 text-3xl font-bold">Pending Invites</h2>
                </header>

                <hr>

                <div class="flex flex-wrap gap-x-8 gap-y-6">
                    @foreach ($invites as $team)
                        <div
                            class="flex flex-col h-24 transition bg-white border border-gray-200 shadow-sm cursor-pointer select-none w-72 rounded-xl hover:shadow-2xl duartion-300">
                            <header class="h-4 bg-gray-200 rounded-tl-xl rounded-tr-xl"></header>
                            <article class="flex flex-col gap-1 px-4 py-2">
                                <h3 class="overflow-hidden font-semibold truncate text-bold">{{ $team->name }}</h3>
                                <p class="flex-grow w-full text-xs break-all line-clamp-2 text-ellipsis max-h-8 ">
                                    {{ $team->description }} </p>
                            </article>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="flex flex-col gap-6">
            <header>
                <h2 class="ml-6 text-3xl font-bold">My Teams</h2>
            </header>

            <hr>

            <div class="flex flex-wrap gap-x-8 gap-y-6">
                <div onclick="ModalView.show('createTeam')"
                    class="flex flex-col items-center justify-center gap-2 text-gray-400 transition duration-300 bg-gray-100 shadow-md cursor-pointer select-none w-72 h-52 rounded-xl hover:shadow-2xl">
                    <x-fas-plus class="w-8 h-8" />
                    <p>Create Team</p>
                </div>

                @foreach ($teams as $team)
                    <a
                        href="{{ route('viewTeam', ['team_id' => $team->id]) }}"
                        class="flex cursor-pointer select-none flex-col transition duration-300 border border-gray-200 shadow-xl rounded-xl h-52 w-72 hover:shadow-2xl bg-pattern-{{ $team->pattern }} overflow-hidden">
                        <div class="flex-grow w-full "></div>
                        <article class="flex flex-col w-full h-20 gap-1 px-4 py-2 bg-white border-t border-t-gray-200">
                            <h3 class="overflow-hidden font-semibold truncate text-bold">{{ $team->name }}</h3>
                            <p class="flex-grow w-full text-xs break-all line-clamp-2 text-ellipsis max-h-8 ">
                                {{ $team->description }} </p>
                        </article>
                    </a>
                @endforeach
            </div>
        </section>
    </div>

@endsection

@pushOnce('page')
    <script>
    </script>
@endPushOnce
