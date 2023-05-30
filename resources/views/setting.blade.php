@extends('layout.page')

@section('content')
    <template is-modal="changeProfile">
        <form class="flex flex-col items-center justify-center w-full h-full gap-6 p-4" method="POST">
            @csrf
            <x-form.file name="image" />
            <div class="" id="image-editor-container"></div>
            <x-form.button type="submit" id="btn-submit" primary>Save</x-form.button>
        </form>
    </template>

    <template is-modal="deleteAccount">
        <form class="flex flex-col items-center justify-center w-full h-full gap-6 p-4" method="POST"
            action="{{ route('doDeactivateUser') }}">
            @csrf
            <input type="hidden" name="id" value="{{ Auth::user()->id }}">
            <p class="mb-6 text-lg text-center"> Are you sure you want to delete your aacount?</p>
            <div class="flex gap-6">
                <x-form.button type="submit">Yes</x-form.button>
                <x-form.button type="button" action="ModalView.close()" primary>No</x-form.button>
            </div>
        </form>
    </template>

    <template is-modal="changePassword">
        <div class="w-full h-full p-4">
            <p class="mb-6 text-lg font-semibold"> Enter Your current and new password below!</p>
            <form class="flex flex-col gap-16" action="{{ route('doUserPasswordUpdate') }}" method="POST">
                @csrf
                <div class="flex flex-col gap-2 ">
                    <x-form.password name="current_password" label="Current Password" />
                    <x-form.password name="new_password" label="New Password" />
                    <x-form.password name="new_password_confirmation" label="New Password Confirmation" />
                </div>
                <x-form.button primary type="submit">Save</x-form.button>
            </form>
    </template>

    <div class="flex flex-col w-full h-full gap-4 p-6 overflow-x-hidden overflow-y-auto">
        <section class="flex flex-col">
            <h2 class="mb-4 ml-6 text-2xl font-bold">My Account</h2>
            <div class="overflow-hidden border-2 shadow-sm rounded-xl border-b-gray-200">
                <header class="relative w-full shadow-md h-14 bg-pattern-triangle">
                    <div class="absolute w-20 h-20 -bottom-10 left-8">
                        <x-avatar :user="Auth::user()" action="javascript:ModalView.show('changeProfile')"
                            class="w-full h-full text-2xl shadow-md" />
                    </div>
                </header>
                <form class="px-10 mb-4" method="POST" action="{{ route('doUserDataUpdate') }}">
                    @csrf
                    <div class="grid items-center grid-cols-2 grid-rows-3 gap-4 align-middle mt-14">
                        <label for="input-text-name" class="text-lg font-semibold">Full Name</label>
                        <x-form.text name="name" :value="Auth::user()->name" />

                        <label for="input-text-email" class="text-lg font-semibold">Email</label>
                        <x-form.text name="email" :value="Auth::user()->email" />

                        <label class="text-lg font-semibold">Change Password</label>
                        <x-form.button type="button" action="ModalView.show('changePassword')">Change</x-form.button>

                        <div class="flex items-center justify-end col-span-2 mt-4">
                            <div class="w-min">
                                <x-form.button primary type="submit">Submit</x-form.button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <section class="flex flex-col">
            <h2 class="mb-4 ml-6 text-2xl font-bold">General</h2>
            <div
                class="grid items-center grid-cols-2 grid-rows-2 gap-4 px-10 py-8 align-middle border-2 shadow-sm pxoverflow-hidden rounded-xl border-b-gray-200">

                <label class="text-lg font-semibold">Logout From Application</label>
                <a href="{{ route('doLogout') }}">
                    <x-form.button type="button">Logout</x-form.button>
                </a>

                <label class="text-lg font-semibold">Delete Account</label>
                <x-form.button primary action="ModalView.show('deleteAccount')" type="button">DELETE</x-form.button>

            </div>
        </section>

    </div>
@endsection

@pushOnce('page')
    <script>
        function renderImage(canvas, blob) {
            const ctx = canvas.getContext('2d')
            const img = new Image()
            img.onload = (event) => {
                URL.revokeObjectURL(event.target.src)
                ctx.drawImage(event.target, 0, 0)
            }
            img.src = URL.createObjectURL(blob)
        }

        function attachBlob(fileInput, blob) {
            const reader = new FileReader();
            reader.onload = function() {
                const image = new Image();
                image.onload = function() {
                    const canvas = document.createElement("canvas"),
                    ctx = canvas.getContext("2d");

                    // Set canvas size to image size
                    canvas.width = image.width;
                    canvas.height = image.height;
                    ctx.drawImage(image, 0, 0);
                    // Set the motified image to be content when the form is submited.
                    document.getElementById("modified_image").value = canvas.toDataURL("image/jpg");
                }
                image.src = reader.result;
            };
            reader.readAsDataURL(this.files[0]);
        };

        ModalView.onShow("changeProfile", function(modal) {
            const editorContainer = modal.querySelector("#image-editor-container");
            const fileSelector = modal.querySelector("#input-file-profile_picture");
            const btnSubmit = modal.querySelector("#btn-submit");
            const form = modal.querySelector("#change-profile-picture");
            const imageInput = modal.querySelector("#image_pfp_hidden");

            let cropper = null;

            fileSelector.addEventListener("change", (event) => {
                console.log("init croppie");
                editorContainer.style.display = "static";
                cropper = new Croppie(editorContainer, {
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
                cropper.bind({
                    url: URL.createObjectURL(event.target.files[0]),
                    orientation: 1
                });
            });

            btnSubmit.addEventListener("click", (e) => {
                e.preventDefault();
                if (cropper == null) {
                    ModalView.close();
                    ToastView.notif("Warning", "No selected file, please upload an image");
                };
                cropper.result('blob').then(function(blob) {
                    let formData = new FormData();
                    formData.append('_token', `{{ csrf_token() }}`);
                    formData.append('image', blob);

                    let form = document.createElement("FORM");
                    form.action = "{{ route('doUserPicturedUpdate') }}";
                    form.method = "POST";

                    axios({
                        method: ('post'),
                        url: `{{ route('doUserPicturedUpdate') }}`,
                        data: formData,
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then((response) => {
                        console.log(response);
                    })
                });
            })

        });

        @if ($errors->any())
            ToastView.notif("Warning", "{{ $errors->first() }}");
        @endif
    </script>
@endPushOnce
