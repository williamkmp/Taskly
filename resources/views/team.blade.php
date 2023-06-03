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
    @endif

    <div class="flex flex-col w-full h-full overflow-auto">
        <header class="flex flex-col gap-4">
            <div class="w-full h-36 bg-pattern-{{ $team->pattern }} border-b border-gray-200"></div>
            <div class="flex items-center w-full gap-6 px-6 overflow-auto">
                @if (Auth::user()->id == $owner->id)
                    <x-avatar name="{{ $team->name }}" asset="{{ $team->image_path }}" class="w-20 text-4xl"
                        action="ModalView.show('changeProfile')">
                        <div
                            class="flex flex-wrap items-center justify-center w-full h-full transition-all bg-black opacity-0 hover:opacity-70">
                            <x-fas-camera class="w-1/3 m-auto h-1/3" />
                        </div>
                    </x-avatar>
                @else
                    <x-avatar name="{{ $team->name }}" asset="{{ $team->image_path }}" class="w-20 text-4xl" />
                @endif

                <div class="flex flex-col justify-center flex-grow h-full gap-2 max-w-[40rem] mr-auto">
                    <h1 class="text-3xl font-bold">{{ $team->name }}</h1>
                    <p>{{ $team->description }}</p>
                </div>

                <article class="h-full py-2 w-max rounded-xl">
                    <div class="grid items-center grid-cols-[6rem_1fr] grid-rows-2">
                        <p class="font-bold">Owner: </p>
                        <div class="flex items-center w-full gap-2">
                            <x-avatar name="{{ $owner->name }}" asset="{{ $owner->image_path }}" class="h-10" />
                            <p>{{ $owner->name }}</p>
                        </div>

                        <p class="font-bold">Created: </p>
                        <p>{{ $team->created_at }}</p>
                    </div>
                </article>
            </div>
        </header>
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
