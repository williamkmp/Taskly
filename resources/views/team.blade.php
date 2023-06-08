@extends('layout.page')

@section('app-header')
    <div class="flex items-center gap-2">
        <h1 class="text-xl font-bold">Team:</h1>
        <p class="text-xl">{{ $team->name }}</p>
    </div>
@endsection

@section('app-side')
    <div class="flex flex-col gap-6 px-8 pl-4 mt-2">
        <a class="w-full p-2 overflow-hidden border-2 border-gray-200 cursor-pointer select-none rounded-xl"
            href="{{ route('viewTeam', ['team_id' => $team->id]) }}">
            <div class="flex items-center w-full gap-2">
                <div class="w-16 h-16">
                    <x-avatar name="{{ $team->name }}" asset="{{ $team->image_path }}"
                        class="!w-16 !h-16 !aspect-square !text-xl" />
                </div>
                <article class="flex flex-col gap-2 text-sm">
                    <h2 class="max-w-full overflow-hidden font-bold truncate">{{ $team->name }}</h2>
                    <p class="text-sm line-clamp-3">
                        {{ $team->description }}
                    </p>
                </article>
            </div>
        </a>

        <section class="w-full overflow-hidden border-2 border-gray-200 cursor-pointer select-none rounded-xl">
            @if (Auth::user()->id == $owner->id)
                <div data-role="menu-item" onclick="ModalView.show('updateTeam')"
                    class="flex items-center w-full gap-3 px-6 py-2 text-black cursor-pointer select-none hover:bg-black hover:text-white">
                    <x-fas-pen class="w-4 h-4" />
                    <p> Edit </p>
                </div>
                <hr class="w-full border">
                <div data-role="menu-item" onclick="ModalView.show('manageMember')"
                    class="flex items-center w-full gap-3 px-6 py-2 text-black cursor-pointer select-none hover:bg-black hover:text-white">
                    <x-fas-user-gear class="w-4 h-4" />
                    <p>Members</p>
                </div>
                <hr class="w-full border">
                <div data-role="menu-item" onclick="ModalView.show('inviteMember')"
                    class="flex items-center w-full gap-3 px-6 py-2 text-black cursor-pointer select-none hover:bg-black hover:text-white">
                    <x-fas-user-plus class="w-4 h-4" />
                    <p>Invite</p>
                </div>
                <hr class="w-full border">
                <div data-role="menu-item" onclick="ModalView.show('createBoard')"
                    class="flex items-center w-full gap-3 px-6 py-2 text-black cursor-pointer select-none hover:bg-black hover:text-white">
                    <x-fas-table-columns class="w-4 h-4" />
                    <p>Add Board</p>
                </div>
                <hr class="w-full border">
                <div data-role="menu-item" onclick="ModalView.show('deleteTeam')"
                    class="flex items-center w-full gap-3 px-6 py-2 text-red-600 cursor-pointer select-none hover:bg-black hover:text-white">
                    <x-fas-trash class="w-4 h-4" />
                    <p>Delete</p>
                </div>
            @else
                <div data-role="menu-item" onclick="ModalView.show('leaveTeam')"
                    class="flex items-center w-full gap-3 px-6 py-2 text-red-600 cursor-pointer select-none hover:bg-black hover:text-white">
                    <x-fas-right-from-bracket class="w-4 h-4" />
                    <p> Leave Team </p>
                </div>
            @endif
        </section>

    </div>
@endsection

@section('content')
    @if (Auth::user()->id == $owner->id)
        <template is-modal="changeProfile">
            <div class="flex flex-col items-center justify-center w-full h-full gap-6 p-4 flex-grow-1">
                <x-form.file name="picture" label="Choose Image" accept="image/png, image/jpeg, image/jpg" />
                <div class="hidden w-full h-36" id="image-editor"></div>
                <x-form.button type="button" id="btn-submit" primary>Save
                </x-form.button>
            </div>
        </template>

        <template is-modal="updateTeam">
            <div class="flex flex-col w-full gap-4 p-4">
                <h1 class="text-3xl font-bold">Edit Team</h1>
                <hr>
                <form action="{{ route('doTeamDataUpdate', ['team_id' => $team->id]) }}" method="POST" class="flex flex-col gap-4">
                    @csrf
                    <input type="hidden" name="team_id" value="{{ $team->id }}">
                    <x-form.text name="team_name" label="Team's Name" value="{{ $team->name }}" required />
                    <x-form.textarea name="team_description" label="Team's Description" value="{{ $team->description }}"
                        required />
                    <div class="flex flex-col w-full gap-2" x-data="{ selected: '{{ $team->pattern }}' }">
                        <label class="pl-6">Team's Background</label>
                        <input type="hidden" id="pattern-field" name="team_pattern" x-bind:value="selected">
                        <div
                            class="flex items-center justify-start w-full max-w-2xl gap-2 px-4 py-2 overflow-hidden overflow-x-scroll border-2 border-gray-200 h-36 rounded-xl">
                            @foreach ($patterns as $pattern)
                                <div x-on:click="selected = '{{ $pattern }}'"
                                    x-bind:class="(selected == '{{ $pattern }}') ? 'border-black' : 'border-gray-200'"
                                    class="{{ $pattern == $team->pattern ? 'order-first' : '' }} h-full flex-shrink-0 border-4 rounded-lg w-36 bg-pattern-{{ $pattern }} hover:border-black">
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

        <template is-modal="createBoard">
            <div class="flex flex-col w-full gap-4 p-4">
                <h1 class="text-3xl font-bold">Create Board</h1>
                <hr>
                <form action="{{ route('createBoard', ['team_id' => $team->id]) }}" method="POST" class="flex flex-col gap-4">
                    @csrf
                    <input type="hidden" name="team_id" value="{{ $team->id }}">
                    <x-form.text name="board_name" label="Board's Name" required />
                    <div class="flex flex-col w-full gap-2" x-data="{ selected: '{{ $backgrounds[0] }}' }">
                        <label class="pl-6">Board's Color</label>
                        <input type="hidden" id="pattern-field" name="board_pattern" x-bind:value="selected">
                        <div
                            class="flex items-center justify-start w-full max-w-2xl gap-2 px-4 py-2 overflow-hidden overflow-x-scroll border-2 border-gray-200 h-36 rounded-xl">
                            @foreach ($backgrounds as $pattern)
                                <div x-on:click="selected = '{{ $pattern }}'"
                                    x-bind:class="(selected == '{{ $pattern }}') ? 'border-black' : 'border-gray-200'"
                                    class="{{ $pattern == $backgrounds[0] ? 'order-first' : '' }} h-full flex-shrink-0 border-4 rounded-lg w-36 bg-grad-{{ $pattern }} hover:border-black">
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

        <template is-modal="manageMember" class="bg-red-200">
            <div class="flex flex-col w-full gap-4 p-4">
                <h1 class="text-3xl font-bold">Manage Members</h1>
                <hr>
                <div class="flex flex-col gap-4">
                    <x-form.text label="Team member" name="member-name" icon="fas-search" />

                    <section
                        class="flex justify-center w-full p-4 overflow-hidden overflow-y-auto border-2 border-black h-80 rounded-xl">
                        <div class="flex flex-wrap w-full max-w-[34rem] min-h-full gap-2">

                            @foreach ($members as $member)
                                <div data-role="member-card" data-email="{{ $member->email }}"
                                    data-name="{{ $member->name }}"
                                    class="flex flex-col items-center justify-center w-32 gap-2 p-2 overflow-hidden border-2 border-gray-300 cursor-pointer select-none h-36 rounded-xl">
                                    <x-avatar name="{{ $member->name }}" asset="{{ $member->image_path }}"
                                        class="!flex-shrink-0 !flex-grow-0 w-12" />
                                    <p class="w-full h-8 text-xs font-bold text-center line-clamp-2">{{ $member->name }}
                                    </p>
                                    <p class="w-full h-8 text-xs font-normal text-center line-clamp-2">
                                        {{ $member->email }}
                                    </p>
                                </div>
                            @endforeach

                        </div>
                    </section>

                    <x-form.button primary type="button" id="save-btn">Delete</x-form.button>
                </div>
            </div>
        </template>

        <template is-modal="inviteMember" class="bg-red-200">
            <div class="flex flex-col w-full gap-4 p-4">
                <h1 class="text-3xl font-bold">Invite People</h1>
                <hr>
                <div class="flex flex-col gap-4">
                    <label for="input-text-inv-email">Enter email address</label>
                    <div class="flex gap-4">
                        <x-form.text name="inv-email" icon="fas-user-plus" placeholder="name@email.com..." />
                        <x-form.button type="button" primary class="w-min" id="add-btn">
                            <x-fas-plus class="w-4 h-4" />
                            Add
                        </x-form.button>
                    </div>

                    <form method="POST" id="invite-members-form" action="{{ route('doInviteMembers', ['team_id' => $team->id]) }}"
                        class="flex justify-center w-full p-4 overflow-hidden overflow-y-auto border-2 border-black h-80 rounded-xl">
                        @csrf
                        <input type="hidden" name="team_id", value="{{ $team->id }}">
                        <div class="flex flex-col w-full gap-2" id="invite-container">

                            {{-- <div class="flex gap-2" id="email-tag-1">
                                <input type="hidden" value="">
                                <p class="flex-grow overflow-hidden truncate">William@email.com</p>
                                <x-form.button outline type="button" action="DOM.find('#email-tag-1')?.remove()"
                                    class="!border-2 !text-sm w-min !px-4">
                                    <x-fas-trash class="w-4 h-4" />
                                </x-form.button>
                            </div> --}}


                        </div>
                    </form>

                    <x-form.button primary type="submit" id="save-btn" form="invite-members-form">Save</x-form.button>
                </div>
            </div>
        </template>

        <template is-modal="deleteTeam">
            <form class="flex flex-col items-center justify-center w-full h-full gap-6 p-4" method="POST"
                action="{{ route('doDeleteTeam', ['team_id' => $team->id]) }}">
                @csrf
                <input type="hidden" name="team_id" value="{{ $team->id }}">
                <p class="mb-6 text-lg text-center"> Are you sure you want to delete this team?</p>
                <div class="flex gap-6">
                    <x-form.button type="submit">Yes</x-form.button>
                    <x-form.button type="button" action="ModalView.close()" primary>No</x-form.button>
                </div>
            </form>
        </template>
    @else
        <template is-modal="leaveTeam">
            <form class="flex flex-col items-center justify-center w-full h-full gap-6 p-4" method="POST"
                action="{{ route('doLeaveTeam', ['team_id' => $team->id]) }}">
                @csrf
                <input type="hidden" name="team_id" value="{{ $team->id }}">
                <p class="mb-6 text-lg text-center"> Are you sure you want to leave this team?</p>
                <div class="flex gap-6">
                    <x-form.button type="submit">Yes</x-form.button>
                    <x-form.button type="button" action="ModalView.close()" primary>No</x-form.button>
                </div>
            </form>
        </template>
    @endif

    <div class="flex flex-col w-full h-full overflow-auto">
        <header class="w-full h-24 flex items-center p-6 bg-pattern-{{ $team->pattern }} border-b border-gray-200">
            <div class="w-20 h-20">
                @if (Auth::user()->id == $owner->id)
                    <x-avatar name="{{ $team->name }}" asset="{{ $team->image_path }}"
                        class="!w-20 !aspect-square !text-4xl" action="ModalView.show('changeProfile')">
                        <div
                            class="flex flex-wrap items-center justify-center w-full h-full transition-all bg-black opacity-0 hover:opacity-70">
                            <x-fas-camera class="w-1/3 m-auto h-1/3" />
                        </div>
                    </x-avatar>
                @else
                    <x-avatar name="{{ $team->name }}" asset="{{ $team->image_path }}"
                        class="!w-20 !aspect-square !text-4xl" />
                @endif
            </div>
        </header>

        <div class="flex flex-grow gap-8 px-6 py-4 overflow-hidden">
            {{-- page left section --}}
            <section class="flex flex-col flex-grow h-full gap-6">

                <section class="flex flex-col gap-4">
                    <header class="flex items-center gap-2 pl-1">
                        <h2 class="text-2xl font-bold">Boards</h2>
                    </header>

                    {{-- Search Bar --}}
                    <form class="flex items-center w-full gap-4" id="search-form" action="{{ route('searchBoard', ['team_id' => $team->id]) }}"
                        method="GET">
                        @csrf
                        <input type="hidden" name="team_id" value="{{ $team->id }}">
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <x-form.text icon="fas-table-columns" name="board_name" placeholder="Boards's name"
                            value="{{ session('__old_board_name') }}" />

                        <x-form.button type="submit" outline class="h-full w-min">
                            <x-fas-magnifying-glass class="w-4 h-4" />Search
                        </x-form.button>
                    </form>

                    <div class="flex flex-wrap mt-2 gap-x-8 gap-y-6">

                        @if ($boards->isEmpty() && Auth::user()->id == $owner->id)
                            <div onclick="ModalView.show('createBoard')"
                                class="flex flex-col items-center justify-center gap-2 text-gray-400 transition duration-300 bg-gray-100 shadow-md cursor-pointer select-none w-72 h-52 rounded-xl hover:shadow-2xl">
                                <x-fas-plus class="w-8 h-8" />
                                <p>Create Board</p>
                            </div>
                        @endif


                        @foreach ($boards as $board)
                            <a href="{{ route('board', ['board_id' => $board->id, 'team_id' => $board->team_id]) }}"
                                class="flex cursor-pointer select-none flex-col transition duration-300 border border-gray-200 shadow-xl rounded-xl h-32 w-72 hover:shadow-2xl bg-grad-{{ $board->pattern }} overflow-hidden">
                                <div class="flex-grow w-full p-4">

                                </div>
                                <article class="flex flex-col w-full gap-1 px-4 py-2 bg-white border-t border-t-gray-200">
                                    <h3 class="overflow-hidden font-semibold truncate text-bold">{{ $board->name }}</h3>
                                </article>
                            </a>
                        @endforeach
                    </div>
                </section>

            </section>

            {{-- page right section --}}
            <aside class="flex flex-col h-full gap-4 w-72">
                <h2 class="ml-4 text-2xl font-bold">Members</h2>

                {{-- members list --}}
                <div
                    class="flex flex-col flex-grow w-full gap-2 p-4 overflow-x-hidden overflow-y-auto border-2 border-gray-200 rounded-xl">
                    <div class="flex items-center gap-4">
                        <x-avatar name="{{ $owner->name }}" asset="{{ $owner->image_path }}"
                            class="!flex-shrink-0 !flex-grow-0 w-12" />
                        <p class="flex-grow truncate">{{ $owner->name }}</p>
                        <x-fas-crown class="w-6 h-6 text-yellow-400 !flex-shrink-0 !flex-grow-0" />
                    </div>

                    @foreach ($members as $member)
                        <div class="flex items-center gap-4">
                            <x-avatar name="{{ $member->name }}" asset="{{ $member->image_path }}"
                                class="!flex-shrink-0 !flex-grow-0 w-12" />
                            <p class="truncate">{{ $member->name }}</p>
                        </div>
                    @endforeach
                </div>
            </aside>
        </div>

    </div>
@endsection

@pushOnce('page')
    <script>
        @if (Auth::user()->id == $owner->id)
            ModalView.onShow('createBoard', (modal) => {
                modal.querySelectorAll("form[action][method]").forEach(
                    form => form.addEventListener("submit", () => PageLoader.show())
                );
            });

            ModalView.onShow('deleteTeam', (modal) => {
                modal.querySelectorAll("form[action][method]").forEach(
                    form => form.addEventListener("submit", () => PageLoader.show())
                );
            });

            ModalView.onShow('updateTeam', (modal) => {
                modal.querySelectorAll("a").forEach(
                    link => link.addEventListener("click", () => PageLoader.show())
                );

                modal.querySelectorAll("form[action][method]").forEach(
                    form => form.addEventListener("submit", () => PageLoader.show())
                );
            });

            ModalView.onShow("changeProfile", (modal) => {
                const imageInput = modal.querySelector("#input-file-picture");
                const btnSubmit = modal.querySelector("#btn-submit");
                const imageEditorContainer = modal.querySelector("#image-editor");
                let imageEditor = new Croppie(imageEditorContainer, {
                    viewport: {
                        width: 150,
                        height: 150,
                        type: 'circle'
                    },

                    boundary: {
                        width: 200,
                        height: 200
                    }
                });

                imageInput.addEventListener("change", (event) => {
                    imageEditorContainer.classList.remove("hidden");
                    imageEditor.bind({
                        url: URL.createObjectURL(event.target.files[0]),
                        orientation: 1
                    });
                });

                btnSubmit.addEventListener("click", async (e) => {
                    try {
                        PageLoader.show();
                        const pfpBlobData = await getCropperImageBlob(imageEditor);
                        let response = await ServerRequest.post("{{ route('doChangeTeamImage', ['team_id' => $team->id]) }}", {
                            image: pfpBlobData,
                            team_id: `{{ $team->id }}`
                        });
                        location.reload();
                    } catch (error) {
                        PageLoader.close();
                        ModalView.close();
                        let errorMessage = getResponseError(error);
                        if (error)
                            ToastView.notif("Warning", errorMessage);
                        else
                            ToastView.notif("Error", "Something went wrong please try again");
                    }
                });

            });

            ModalView.onShow('manageMember', (modal) => {
                const searchMember = modal.querySelector("#input-text-member-name");
                const memberCards = modal.querySelectorAll(`div [data-role="member-card"]`);
                const saveBtn = modal.querySelector("#save-btn");

                memberCards.forEach(card => card.addEventListener("click", () => {
                    card.classList.toggle("bg-red-200");
                    card.classList.toggle("is-delete");
                }))

                searchMember.addEventListener("input", (event) => {
                    let search = event.target.value?.toLowerCase().trim();
                    memberCards.forEach(card => {
                        if (card.classList.contains("is-delete"))
                            return;
                        let name = card.dataset.name.toLowerCase();
                        let email = card.dataset.email.toLowerCase();
                        card.style.display = "flex";
                        if (!name.includes(search) && !email.includes(search))
                            card.style.display = "none";
                    });
                });

                saveBtn.addEventListener("click", async () => {
                    PageLoader.show();
                    let deleteEmailList = Array.from(memberCards)
                        .filter(card => card.classList.contains("is-delete"))
                        .map(card => card.dataset.email);

                    try {
                        await ServerRequest.post("{{ route('deleteTeamMember', ['team_id' => $team->id]) }}", {
                            team_id: `{{ $team->id }}`,
                            user_id: `{{ Auth::user()->id }}`,
                            emails: deleteEmailList,
                        });
                        location.reload();
                    } catch (error) {
                        console.log(error);
                        PageLoader.close();
                        ModalView.close();
                        let errorMessage = getResponseError(error);
                        if (errorMessage)
                            ToastView.notif("Warning", errorMessage);
                        else
                            ToastView.notif("Error", "Something went wrong please try again");
                    }
                })
            });

            ModalView.onShow("inviteMember", (modal) => {
                const addBtn = modal.querySelector('#add-btn');
                const saveBtn = modal.querySelector('#save-btn');
                const emailField = modal.querySelector('#input-text-inv-email');
                const inviteList = modal.querySelector('#invite-container');

                emailField.addEventListener("keypress", () => {
                    if (event.key === "Enter") {
                        event.preventDefault();
                        handleInsert();
                    }
                });

                addBtn.addEventListener('click', handleInsert);

                saveBtn.addEventListener('click', () => PageLoader.show());

                function handleInsert() {
                    const email = emailField.value.trim();

                    if (email === "") return;
                    if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) return;

                    emailField.value = "";
                    const id = DOM.newid();
                    let emailtag = DOM.create(`
                    <div class="flex gap-2" id="email-tag-${id}">
                        <input type="hidden" name="emails[]" value="${email}">
                        <p class="flex-grow overflow-hidden truncate">
                            ${email}
                        </p>
                        <button onclick="DOM.find('#email-tag-${id}')?.remove()" type="button" class="flex items-center justify-center w-full gap-2 px-6 py-1 text-base font-bold border-4 border-black rounded-full bg-white text-black hover:bg-black hover:text-white !border-2 !text-sm w-min !px-4">
                                <svg class="w-4 h-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Free 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2023 Fonticons, Inc. --><path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"></path></svg>
                        </button>
                    </div>
                    `);

                    inviteList.append(emailtag);
                }

            })
        @endif

        @if ($errors->any())
            ToastView.notif("Warning", "{{ $errors->first() }}");
        @endif
    </script>
@endPushOnce
