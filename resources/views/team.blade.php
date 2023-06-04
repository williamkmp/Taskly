@extends('layout.page')

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
                <form action="{{ route('doTeamDataUpdate') }}" method="POST" class="flex flex-col gap-4">
                    @csrf
                    <input type="hidden" name="team_id" value="{{ $team->id }}">
                    <x-form.text name="team_name" label="Team's Name" value="{{ $team->name }}" required />
                    <x-form.textarea name="team_description" label="Team's Description" value="{{ $team->description }}"
                        required />
                    <x-form.button class="mt-4" type="submit" primary>Submit</x-form.button>
                </form>
            </div>
        </template>
    @endif

    <div class="flex flex-col w-full h-full overflow-auto">
        <header class="w-full h-24  bg-pattern-{{ $team->pattern }} border-b border-gray-200">
        </header>

        <div class="flex flex-grow gap-8 px-6 py-4 overflow-hidden">
            {{-- page left section --}}
            <section class="flex flex-col flex-grow h-full gap-6">
                <header class="flex items-center w-full gap-6">

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

                    {{-- team informations --}}
                    <article class="flex flex-col flex-grow gap-2 text-sm">
                        <h2 class="text-xl font-bold">{{ $team->name }}</h2>
                        <p class="line-clamp-3">
                            {{ $team->description }}
                        </p>
                        <p>
                            <span class="font-bold">Created: </span> {{ $team->created_at }}
                        </p>
                    </article>

                    {{-- team controls --}}
                    <div class="flex flex-col justify-end w-40 h-full gap-2">
                        @if (Auth::user()->id == $owner->id)
                            <x-form.button outline type="button" action="ModalView.show('updateTeam')"
                                class="!border-2 !text-sm h-min !px-4">
                                <x-fas-gear class="w-4 h-4" />
                                Settings
                            </x-form.button>
                            <x-form.button outline type="button" action="ModalView.show('updateTeam')"
                                class="!border-2 !text-sm h-min !px-4">
                                <x-fas-users class="w-4 h-4" />
                                Members
                            </x-form.button>
                        @else
                            <x-form.button outline type="button" action="ModalView.show('updateTeam')"
                                class="!border-2 !text-sm h-min !px-4">
                                <x-fas-right-from-bracket class="w-4 h-4" />
                                Leave Team
                            </x-form.button>
                        @endif
                    </div>
                </header>

                <hr />

                <form class="flex items-center w-full gap-4" id="search-form" action="{{ route("searchBoard") }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="team_id" value="{{ $team->id }}">
                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                    <x-form.text icon="fas-table-columns" name="board_name" placeholder="Boards's name"
                        value="{{ session('__old_board_name') }}"/>

                    <x-form.button type="submit" primary class="h-full w-min">
                        <x-fas-magnifying-glass class="w-4 h-4" />Search
                    </x-form.button>

                </form>

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
                        let response = await ServerRequest.post("{{ route('doChangeTeamImage') }}", {
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
        @endif

        @if ($errors->any())
            ToastView.notif("Warning", "{{ $errors->first() }}");
        @endif
    </script>
@endPushOnce
