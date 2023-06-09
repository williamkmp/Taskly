@props(['teamid', 'isowner'])
<div style="display: none">

    @if (isset($isowner) && $isowner == true)
        <template is-modal="updateColumn">
            <div class="flex flex-col w-full gap-4 p-4">
                <form class="flex flex-col gap-4" method="POST">
                    @csrf
                    <input type="hidden" name="column_id" id="column_id">
                    <x-form.text name="column_name" label="Column's Name" required />
                    <x-form.button class="mt-4" type="submit" primary>Add</x-form.button>
                </form>
            </div>
        </template>

        <template is-modal="deleteColumn">
            <form class="flex flex-col items-center justify-center w-full h-full gap-6 p-4" method="POST">
                @csrf
                <input type="hidden" name="column_id" id="column_id">
                <p class="mb-6 text-lg text-center"> Are you sure you want to delete this column?</p>
                <div class="flex gap-6">
                    <x-form.button type="submit">Yes</x-form.button>
                    <x-form.button type="button" action="ModalView.close()" primary>No</x-form.button>
                </div>
            </form>
        </template>
    @endif

    <template id="column">
        <div data-role="column"
            class="flex flex-col flex-shrink-0 max-h-full border-2 shadow-lg group h-min border-slate-50 w-72 rounded-xl bg-slate-100">
            <header class="flex items-center gap-2 px-4 py-2 select-none rounded-t-xl" draggable="true">
                <h2 class="w-full overflow-hidden text-sm font-bold truncate"></h2>
                @if (isset($isowner) && $isowner == true)
                    <div type="button" id="col-upd-btn"
                        class="p-2 text-gray-600 transition rounded-full opacity-0 bg-slate-200 hover:bg-slate-300 group-hover:opacity-100 ">
                        <x-fas-pen class="w-[12px] h-[12px]" />
                    </div>
                    <div type="button" id="col-del-btn"
                        class="p-2 text-gray-600 transition rounded-full opacity-0 bg-slate-200 hover:bg-slate-300 group-hover:opacity-100 ">
                        <x-fas-trash class="w-[12px] h-[12px]" />
                    </div>
                @endif
            </header>
            <hr>
            <section class="w-full overflow-hidden overflow-y-auto">
                <div class="flex flex-col gap-3 p-2" id="card-container">

                </div>
            </section>
            <form class="flex flex-col w-full gap-1 px-2 mt-2 overflow-hidden text-sm max-h-0">
                <textarea
                    class="w-full px-4 py-2 mb-1 bg-white border border-gray-200 shadow cursor-pointer resize-none select-none line-clamp-3 rounded-xl"
                    id="card" cols="30" rows="3" maxlength="95"></textarea>
                <button id="btn-submit"
                    class="flex items-center w-full gap-2 py-1 pl-4 mb-2 transition select-none rounded-2xl hover:bg-slate-200">
                    <x-fas-plus class="w-4 h-4" />
                    Add Card...
                </button>
            </form>
            <button id="btn-add"
                class="flex items-center gap-2 py-1 pl-4 mx-2 mb-2 text-sm transition select-none rounded-2xl hover:bg-slate-200">
                <x-fas-plus class="w-4 h-4" />
                Add Card...
            </button>
        </div>
    </template>
</div>


@pushOnce('component')
    <Script>
        const columnTemplate = document.querySelector("template#column");
        class Column {
            constructor(board, id, name, cards = []) {
                this.board = board;
                const content = columnTemplate.content.cloneNode(true);
                const node = document.createElement("div");
                node.append(content);

                this.ref = node.children[0];
                this.ref.querySelector("header > h2").textContent = name;
                this.ref.dataset.id = id;
                this.ref.dataset.name = name;

                const btnAdd = this.ref.querySelector(":scope > button#btn-add");
                const btnSubmit = this.ref.querySelector(":scope > form > button#btn-submit");
                const formAdd = this.ref.querySelector(":scope > form");
                const inputCard = this.ref.querySelector(":scope > form > textarea#card");
                const cardContainer = this.ref.querySelector("#card-container");
                const colHeader = this.ref.querySelector(":scope > header");
                colHeader.setAttribute('draggable', (id != null));

                @if (isset($isowner) && $isowner == true)
                    this.ref.querySelector(":scope > header > #col-upd-btn").addEventListener("click", () => ModalView
                        .show("updateColumn", {
                            name: this.ref.dataset.name,
                            id: this.ref.dataset.id
                        }));
                    this.ref.querySelector(":scope > header > #col-del-btn").addEventListener("click", () => ModalView
                        .show("deleteColumn", {
                            name: this.ref.dataset.name,
                            id: this.ref.dataset.id
                        }));

                    ModalView.onShow("updateColumn", (modal, payload) => {
                        this.board.IS_EDITING = true;
                        const board_id = this.board.ref.dataset.id;
                        const form = modal.querySelector("form");
                        const inputTag = modal.querySelector("input#input-text-column_name");
                        const idTag = modal.querySelector("input#column_id");
                        inputTag.value = payload.name;
                        idTag.value = payload.id;
                        form.action = `{{ url('team/' . $teamid . '/board/${board_id}/column/update') }}`;
                        form.addEventListener("submit", () => PageLoader.show());
                    });
                    ModalView.onShow("deleteColumn", (modal, payload) => {
                        this.board.IS_EDITING = true;
                        const board_id = this.board.ref.dataset.id;
                        const form = modal.querySelector("form");
                        const idTag = modal.querySelector("input#column_id");
                        idTag.value = payload.id;
                        form.action = `{{ url('team/' . $teamid . '/board/${board_id}/column/delete') }}`;
                        form.addEventListener("submit", () => PageLoader.show());
                    });

                    ModalView.onClose("updateColumn", () => {
                        this.board.IS_EDITING = false;
                    });
                    ModalView.onClose("deleteColumn", () => {
                        this.board.IS_EDITING = false;
                    });
                @endif

                colHeader.addEventListener('dragstart', () => {
                    this.board.IS_EDITING = true;
                    this.ref.classList.add("is-dragging");
                    this.ref.classList.add("opacity-50");
                });

                colHeader.addEventListener('dragend', () => {
                    this.ref.classList.remove("is-dragging");
                    colHeader.setAttribute('draggable', false);
                    this.ref.classList.remove("opacity-50");
                    const board_id = this.board.ref.dataset.id;

                    ServerRequest.post(`{{ url('team/' . $teamid . '/board/${board_id}/column/reorder') }}`, {
                            middle_id: this.ref.dataset.id,
                            right_id: this.ref.nextElementSibling?.dataset?.id || null,
                            left_id: this.ref.previousElementSibling?.dataset?.id || null,
                        })
                        .then((response) => {
                            this.board.IS_EDITING = false;
                            colHeader.setAttribute('draggable', true);
                            console.log(response.data);
                        });
                });

                btnAdd.addEventListener("click", () => {
                    board.IS_EDITING = true;
                    btnAdd.style.display = "none";
                    formAdd.classList.remove("max-h-0");
                    inputCard.focus();
                });

                cardContainer.addEventListener("dragover", (e) => {
                    e.preventDefault();
                    let currentDraggingCard = DOM.find("div[data-role='card'].is-dragging");
                    if (currentDraggingCard == null) return;
                    let closestBottomCardFromMouse = null;
                    let closestOffset = Number.NEGATIVE_INFINITY;
                    let staticCards = cardContainer.querySelectorAll(
                        ":scope > div[data-role='card']:not(.is-dragging)");

                    //calculate closestTask
                    staticCards.forEach((card) => {
                        let {
                            top,
                            bottom
                        } = card.getBoundingClientRect();

                        let offset = event.clientY - ((top + bottom) / 2);

                        if (offset < 0 && offset > closestOffset) {
                            closestOffset = offset;
                            closestBottomCardFromMouse = card;
                        }
                    });

                    if (closestBottomCardFromMouse) {
                        cardContainer.insertBefore(
                            currentDraggingCard,
                            closestBottomCardFromMouse
                        );
                    } else {
                        cardContainer.appendChild(currentDraggingCard);
                    }

                })

                inputCard.addEventListener("blur", () => {
                    btnSubmit.click();
                });

                inputCard.addEventListener("keydown", (event) => {
                    if (event.key === "Enter")
                        btnSubmit.click();
                });

                btnSubmit.addEventListener("click", (event) => {
                    event.preventDefault();
                    formAdd.classList.add("max-h-0");
                    btnAdd.style.display = "flex";
                    const cardValue = inputCard.value.trim();
                    inputCard.value = "";
                    if (cardValue === "") {
                        board.IS_EDITING = false
                        return;
                    };

                    // submit
                    const board_id = this.board.ref.dataset.id;
                    const column_id = this.ref.dataset.id;

                    const newCard = new Card(null, cardValue);
                    newCard.mountTo(this);
                    ServerRequest.post(
                            `{{ url('team/' . $teamid . '/board/${board_id}/column/${column_id}/card') }}`, {
                                name: cardValue
                            })
                        .then((response) => {
                            console.log(response.data.name);
                            newCard.setId(response.data.id);
                            board.IS_EDITING = false;
                        });
                });

                for (const cardData of cards) {
                    const card = new Card(cardData.id, cardData.name, this.board);
                    card.mountTo(this);
                }

            }

            mountTo(board) {
                this.board = board;
                board.ref.append(this.ref);
            }

            setId(id) {
                this.ref.dataset.id = id;
            }

        }
    </Script>
@endPushOnce
