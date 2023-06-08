@extends('layout.page')

@section('app-header')
    <div class="flex items-center gap-2">
        <h1 class="text-xl font-bold">Board: </h1>
        <p class="text-xl">{{ $board->name }}</p>
    </div>
@endsection


@section('app-side')
    <div class="flex flex-col gap-6 px-8 pl-4 mt-2">
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

        <section class="w-full overflow-hidden border-2 border-gray-200 cursor-pointer select-none rounded-xl">
            @if (Auth::user()->id == $owner->id)
                <div data-role="menu-item" onclick="ModalView.show('updateBoard')"
                    class="flex items-center w-full gap-3 px-6 py-2 text-black cursor-pointer select-none hover:bg-black hover:text-white">
                    <x-fas-pen class="w-4 h-4" />
                    <p> Edit </p>
                </div>
            @endif
        </section>

    </div>
@endsection


@section('content')
    <x-card />
    <x-column />
    <div class="w-full h-full min-h-full overflow-hidden overflow-x-scroll bg-grad-{{ $board->pattern }}">
        <section class="flex h-full min-w-full gap-4 p-4">
            <div class="flex h-full gap-4" id="column-container" data-role="board" data-id="{{ $board->id }}">
            </div>
            <div onclick="ModalView.show('addCol')"
                class="flex flex-col flex-shrink-0 gap-2 px-4 py-2 transition shadow-lg cursor-pointer select-none h-min w-72 rounded-xl bg-slate-50 hover:scale-105 hover:relative">
                <div class="flex items-center justify-center gap-4 text-black">
                    <x-fas-plus class="w-4 h-4" />
                    <p>Add...</p>
                </div>

            </div>
        </section>
    </div>

    {{-- modal declaration --}}
    <template is-modal="updateBoard">
        <div class="flex flex-col w-full gap-4 p-4">
            <h1 class="text-3xl font-bold">Edit Board</h1>
            <hr>
            <form action="{{ route('updateBoard', ['board_id' => $board->id]) }}" method="POST"
                class="flex flex-col gap-4">
                @csrf
                <input type="hidden" name="board_id" value="{{ $board->id }}">
                <x-form.text name="board_name" label="Board's Name" value="{{ $board->name }}" required />

                <div class="flex flex-col w-full gap-2" x-data="{ selected: '{{ $board->pattern }}' }">
                    <label class="pl-6">Board's Color</label>
                    <input type="hidden" id="pattern-field" name="board_pattern" x-bind:value="selected">
                    <div
                        class="flex items-center justify-start w-full max-w-2xl gap-2 px-4 py-2 overflow-hidden overflow-x-scroll border-2 border-gray-200 h-36 rounded-xl">
                        @foreach ($patterns as $pattern)
                            <div x-on:click="selected = '{{ $pattern }}'"
                                x-bind:class="(selected == '{{ $pattern }}') ? 'border-black' : 'border-gray-200'"
                                class="{{ $pattern == $board->pattern ? 'order-first' : '' }} h-full flex-shrink-0 border-4 rounded-lg w-36 bg-grad-{{ $pattern }} hover:border-black">
                                <div x-bind:class="(selected == '{{ $pattern }}') ? 'opacity-100' : 'opacity-0'"
                                    class="flex items-center justify-center w-full h-full">
                                    <x-fas-circle-check class="w-6 h-6" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <x-form.button class="mt-4" type="submit" primary>Save</x-form.button>
            </form>
        </div>
    </template>

    <template is-modal="addCol">
        <div class="flex flex-col w-full gap-4 p-4">
            <form class="flex flex-col gap-4">
                <x-form.text name="column_name" label="Column's Name" required />
                <x-form.button class="mt-4" type="submit" primary>Add</x-form.button>
            </form>
        </div>
    </template>
@endsection

@pushOnce('page')
    <script>
        class Board {
            constructor(boardJson) {
                this.id = boardJson.id;
                this.DRAG_MODE = null;
                this.IS_EDITING = false;
                this.ref = DOM.find("#column-container");
                this.columnList = [];
                for (const column of boardJson.columns) {
                    this.addCol(
                        column.id,
                        column.name,
                        column.cards,
                    )
                }
                setInterval(() => {
                    this.refresh();
                }, 2000);

                this.ref.addEventListener("dragover", (e) => {
                    e.preventDefault();
                    let currentDraggingCol = DOM.find("div[data-role='column'].is-dragging");
                    if (currentDraggingCol == null) return;
                    let closestBottomColFromMouse = null;
                    let closestOffset = Number.NEGATIVE_INFINITY;
                    let staticCols = this.ref.querySelectorAll(
                        ":scope > div[data-role='column']:not(.is-dragging)");

                    //calculate closestTask
                    staticCols.forEach((card) => {
                        let {
                            left,
                            right
                        } = card.getBoundingClientRect();

                        let offset = event.clientX - ((left + right) / 2);

                        if (offset < 0 && offset > closestOffset) {
                            closestOffset = offset;
                            closestBottomColFromMouse = card;
                        }
                    });

                    if (closestBottomColFromMouse) {
                        this.ref.insertBefore(
                            currentDraggingCol,
                            closestBottomColFromMouse
                        );
                    } else {
                        this.ref.appendChild(currentDraggingCol);
                    }

                })
            }

            addCol(id, name, cards) {
                let column = new Column(this, id, name, cards);
                this.columnList.push(column);
                column.mountTo(this);
            }

            refresh() {
                if (this.IS_EDITING) return;
                ServerRequest.get(`{{ route('boardJson', ['board_id' => $board->id]) }}`)
                    .then(response => {
                        if (this.IS_EDITING) return;
                        this.columnList = [];
                        const json = response.data;
                        this.ref.innerHTML = "";
                        for (const column of json.columns) {
                            this.addCol(
                                column.id,
                                column.name,
                                column.cards,
                            )
                        }
                        console.log("[BOARD]: refreshed...");
                    });
            }
        }

        const board = new Board(@json($board));

        ModalView.onShow("addCol", (modal) => {
            board.IS_EDITING = true;
            modal.querySelector("#input-text-column_name").focus();
            modal.querySelector("form").addEventListener("submit", (e) => {
                e.preventDefault();
                const colName = modal.querySelector("#input-text-column_name").value.trim();
                if (colName === "") {
                    ModalView.close();
                    board.IS_EDITING = false;
                    return;
                }

                const column = new Column(board, null, colName);
                column.mountTo(board);
                ModalView.close();
                ServerRequest.post(
                    `{{ route('addCol', ['board_id' => $board->id]) }}`, {
                        board_id: `{{ $board->id }}`,
                        column_name: colName
                    }).then(response => {
                    column.setId(response.data.id);
                }).then(response =>{
                    board.IS_EDITING = false
                });
            });
        });

        @if ($errors->any())
            ToastView.notif("Warning", "{{ $errors->first() }}");
        @endif
    </script>
@endPushOnce
