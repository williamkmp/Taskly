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
        <header class="flex flex-col gap-4">
            <div class="w-full h-36 bg-pattern-{{ $team->pattern }} border-b border-gray-200"></div>
            <div class="flex items-center w-full gap-6 px-6 overflow-auto">
                @if (Auth::user()->id == $owner->id)
                    <x-avatar name="{{ $team->name }}" asset="{{ $team->image_path }}" class="w-20 !text-4xl"
                        action="ModalView.show('changeProfile')">
                        <div
                            class="flex flex-wrap items-center justify-center w-full h-full transition-all bg-black opacity-0 hover:opacity-70">
                            <x-fas-camera class="w-1/3 m-auto h-1/3" />
                        </div>
                    </x-avatar>
                @else
                    <x-avatar name="{{ $team->name }}" asset="{{ $team->image_path }}" class="w-20 !text-4xl" />
                @endif

                <div class="flex flex-col justify-center flex-grow h-full gap-2 max-w-[40rem] mr-auto">
                    <div class="flex items-center gap-6">
                        <h1 class="text-3xl font-bold">{{ $team->name }}</h1>
                        @if (Auth::user()->id == $owner->id)
                            <x-form.button outline type="button" action="ModalView.show('updateTeam')"
                                class="!border-2 !text-sm w-min h-min !px-4">
                                <x-fas-pen class="w-4 h-4" />
                                Edit..
                            </x-form.button>
                        @endif
                    </div>
                    <p>{{ $team->description }}</p>
                </div>

                <article class="h-full py-2 w-80 rounded-xl">
                    <div class="grid items-center grid-cols-[6rem_1fr] grid-rows-2">
                        <p class="font-bold">Owner: </p>
                        <div class="flex items-center w-full gap-2 truncate">
                            <x-avatar name="{{ $owner->name }}" asset="{{ $owner->image_path }}" class="w-10" />
                            <p class="truncate">{{ $owner->name }}</p>
                        </div>

                        <p class="font-bold">Created: </p>
                        <p>{{ $team->created_at }}</p>
                    </div>
                </article>
            </div>
        </header>

        <div class="flex flex-grow gap-16 p-6 mt-6 ">
            <section class="flex flex-col flex-grow gap-6">
                <header>
                    <h2 class="text-3xl font-bold">My Teams</h2>
                </header>

                <hr>

                <div class="flex flex-wrap gap-x-8 gap-y-6">
                    @if (Auth::user()->id == $owner->id)
                        <div onclick="ModalView.show('createTeam')"
                            class="flex flex-col items-center justify-center gap-2 text-gray-400 transition duration-300 bg-gray-100 shadow-md cursor-pointer select-none w-72 h-52 rounded-xl hover:shadow-2xl">
                            <x-fas-plus class="w-8 h-8" />
                            <p>Create Team</p>
                        </div>
                    @endif

                    {{-- @foreach ($teams as $team)
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
                    @endforeach --}}
                </div>
            </section>

            <div class="w-80 h-full max-h-[40rem] flex flex-col gap-6">
                <header>
                    <h2 class="text-3xl font-bold ">Team members</h2>
                </header>
                <aside
                    class="flex flex-col w-full h-full gap-4 p-4 overflow-x-hidden overflow-y-auto border-2 border-gray-200 rounded-xl">
                    @if (Auth::user()->id == $owner->id)
                        <x-form.button primary type="button" class="!border-2 !text-sm h-min !px-4">
                            <x-fas-user-plus class="w-4 h-4" />
                            Manage Members
                        </x-form.button>
                    @endif

                    <div class="flex flex-col flex-grow w-full gap-2 overflow-x-hidden overflow-y-auto">
                        <div class="flex items-center gap-4">
                            <x-avatar name="{{ $owner->name }}" asset="{{ $owner->image_path }}" class="w-12" />
                            <p class="truncate">{{ $owner->name }}</p>
                            <x-fas-crown class="w-6 h-6 text-yellow-400" />
                        </div>

                        @foreach ($members as $member)
                            <div class="flex items-center gap-4">
                                <x-avatar name="{{ $member->name }}" asset="{{ $member->image_path }}" class="w-12" />
                                <p class="truncate">{{ $member->name }}</p>
                            </div>
                        @endforeach
                    </div>
                </aside>
            </div>
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
