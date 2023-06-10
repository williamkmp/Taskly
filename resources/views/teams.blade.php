@extends('layout.page')

@section('app-header')
    <h1 class="text-xl font-bold">Teams</h1>
@endsection

@section('app-side')
    <div class="flex flex-col gap-6 px-8 pl-4 mt-2">

        <section class="w-full overflow-hidden border-2 border-gray-200 cursor-pointer select-none rounded-xl">
            <div data-role="menu-item" onclick="ModalView.show('createTeam')"
                class="flex items-center w-full gap-3 px-6 py-2 text-black cursor-pointer select-none hover:bg-black hover:text-white">
                <x-fas-cube class="w-4 h-4" />
                <p> Add Team </p>
            </div>
        </section>

    </div>
@endsection

@section('content')
    <template is-modal="createTeam">
        <div class="flex flex-col w-full gap-4 p-4">
            <h1 class="text-3xl font-bold">Create Team</h1>
            <hr>
            <form action="{{ route('doCreateTeam') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <x-form.text name="team_name" label="Team's Name" required />
                <x-form.textarea name="team_description" label="Team's Description" required />
                <x-form.button class="mt-4" type="submit" primary>Submit</x-form.button>
            </form>
        </div>
    </template>

    <template is-modal="acceptInvite">
        <div class="flex flex-col w-full gap-4 p-4">
            <h1 class="text-3xl font-bold">Team Invite</h1>
            <div class="flex flex-col gap-4">
                <header class="w-full p-4 h-28" id="header-overlay">
                    <div
                        class="relative flex items-center justify-center w-20 overflow-hidden bg-black border-4 border-white rounded-full aspect-square">
                        <img id="team-image" src="" alt=""
                            class="absolute top-0 left-0 z-40 object-fill w-full h-full">
                        <p class="text-2xl font-bold text-white" id="team-initial"></p>
                    </div>
                </header>
                <hr>

                <article class="flex flex-col gap-2">
                    <p>you are invited to join team <span id="team-name" class="font-bold"></span></p>
                    <p><span class="font-bold">Description: </span><span id="team-description"></span></p>
                </article>

                <article class="flex items-center gap-2 mt-2">

                    <p>Sincerely, <span id="owner-name" class="font-bold"></span></p>
                    <div
                        class="relative flex items-center justify-center w-12 overflow-hidden bg-black border-4 border-white rounded-full aspect-square">
                        <img id="owner-image" src="" alt=""
                            class="absolute top-0 left-0 z-40 object-fill w-full h-full">
                        <p class="text-base font-bold text-white" id="owner-initial"></p>
                    </div>

                </article>

                <form class="flex gap-4">
                    <x-form.button type="submit" primary id="btn-yes">Accept</x-form.button>
                    <x-form.button type="submit" id="btn-no">Reject</x-form.button>
                </form>
            </div>
        </div>
    </template>

    <template is-modal="createTeam">
        <div class="flex flex-col w-full gap-4 p-4">
            <h1 class="text-3xl font-bold">Create Team</h1>
            <hr>
            <form action="{{ route('doCreateTeam') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <x-form.text name="team_name" label="Team's Name" required />
                <x-form.textarea name="team_description" label="Team's Description" required />

                <div class="flex flex-col w-full gap-2" x-data="{ selected: '{{ $patterns[0] }}' }">
                    <label class="pl-6">Team's Background</label>
                    <input type="hidden" id="pattern-field" name="team_pattern" x-bind:value="selected">
                    <div
                        class="flex items-center justify-start w-full max-w-2xl gap-2 px-4 py-2 overflow-hidden overflow-x-scroll border-2 border-gray-200 h-36 rounded-xl">
                        @foreach ($patterns as $pattern)
                            <div x-on:click="selected = '{{ $pattern }}'"
                                x-bind:class="(selected == '{{ $pattern }}') ? 'border-black' : 'border-gray-200'"
                                class="{{ $pattern == $patterns[0] ? 'order-first' : '' }} h-full flex-shrink-0 border-4 rounded-lg w-36 bg-pattern-{{ $pattern }} hover:border-black">
                                <div x-bind:class="(selected == '{{ $pattern }}') ? 'opacity-100' : 'opacity-0'"
                                    class="flex items-center justify-center w-full h-full">
                                    <x-fas-circle-check class="w-6 h-6" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <x-form.button class="mt-4" type="submit" primary>Submit</x-form.button>
            </form>
        </div>
    </template>

    <div class="flex flex-col w-full h-full gap-6 px-8 py-6 overflow-auto">
        <header class="w-full">
            <form class="flex items-center gap-4" id="search-form" action="{{ route('searchTeam') }}" method="GET">
                @csrf
                <x-form.text icon="fas-cube" name="team_name" placeholder="Team's name"
                    value="{{ session('__old_team_name') }}" />
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
                        <div onclick="ModalView.show('acceptInvite', { team_id: '{{ $team->id }}'  })"
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
                @if ($teams->isEmpty())
                    <div onclick="ModalView.show('createTeam')"
                        class="flex flex-col items-center justify-center gap-2 text-gray-400 transition duration-300 bg-gray-100 shadow-md cursor-pointer select-none w-72 h-52 rounded-xl hover:shadow-2xl">
                        <x-fas-plus class="w-8 h-8" />
                        <p>Create Team</p>
                    </div>
                @endif
                @foreach ($teams as $team)
                    <a href="{{ route('viewTeam', ['team_id' => $team->id]) }}"
                        class="flex cursor-pointer select-none flex-col transition duration-300 border border-gray-200 shadow-xl rounded-xl h-52 w-72 hover:shadow-2xl bg-pattern-{{ $team->pattern }} overflow-hidden">
                        <div class="flex-grow w-full p-4">
                            <x-avatar name="{{ $team->name }}" asset="{{ $team->image_path }}" class="h-12" />
                        </div>
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
        ModalView.onShow("createTeam", (modal) =>{
            modal.querySelectorAll("form[method][action]").forEach(
                form => form.addEventListener("submit", () => PageLoader.show())
            );
        });

        ModalView.onShow("acceptInvite", async (modal, payload) => {
            PageLoader.show();
            const header = modal.querySelector("#header-overlay");
            const teamImage = modal.querySelector("#team-image");
            const ownerImage = modal.querySelector("#owner-image");
            const teamInitial = modal.querySelector("#team-initial");
            const teamDescription = modal.querySelector("#team-description");
            const ownerInitial = modal.querySelector("#owner-initial");
            const teamName = modal.querySelector("#team-name");
            const ownerName = modal.querySelector("#owner-name");
            const btnYes = modal.querySelector("#btn-yes");
            const btnNo = modal.querySelector("#btn-no");

            const response = await ServerRequest.get(
                `{{ url('team/${payload.team_id}/invite/' . Auth::user()->id) }}`)

            header.classList.add(`bg-pattern-${response.data.team_pattern}`);
            teamDescription.textContent = response.data.team_description;
            teamName.textContent = response.data.team_name;
            ownerName.textContent = response.data.owner_name;
            teamInitial.textContent = response.data.team_initial;
            ownerInitial.textContent = response.data.owner_initial;
            teamImage.src = response.data.team_image;
            ownerImage.src = response.data.owner_image;
            btnYes.formAction = response.data.accept_url;
            btnNo.formAction = response.data.reject_url;
            if (!response.data.team_image) teamImage.style.display = "none";
            if (!response.data.owner_image) ownerImage.style.display = "none";
            modal.querySelectorAll("a").forEach(
                link => link.addEventListener("click", () => PageLoader.show())
            );

            modal.querySelectorAll("button[type='submit']").forEach(
                form => form.addEventListener("click", () => PageLoader.show())
            );

            PageLoader.close();
        });
    </script>
@endPushOnce
