<template id="column">
    <div data-role="column"
        class="flex flex-col flex-shrink-0 max-h-full px-4 py-2 border-2 shadow-lg group h-min border-slate-50 w-72 rounded-xl bg-slate-50">
        <header class="select-none">
            <h2 class="w-full overflow-hidden text-sm font-bold truncate"></h2>
        </header>
        <section class="w-full mt-2 overflow-hidden overflow-y-auto">
            <div class="flex flex-col gap-3 py-2" id="card-container">

            </div>
        </section>
        <form class="flex flex-col w-full gap-2 mt-2 overflow-hidden text-sm max-h-0">
            <textarea
                class="w-full px-4 py-2 mb-1 bg-white border border-gray-200 shadow cursor-pointer resize-none select-none line-clamp-3 rounded-xl"
                id="card" cols="30" rows="2" maxlength="100"></textarea>
            <button id="btn-submit"
                class="flex items-center w-full gap-2 py-1 pl-4 transition select-none rounded-2xl hover:bg-slate-200">
                <x-fas-plus class="w-4 h-4" />
                Add Card...
            </button>
        </form>
        <button id="btn-add"
            class="flex items-center w-full gap-2 py-1 pl-4 mt-0 text-sm transition select-none rounded-2xl hover:bg-slate-200">
            <x-fas-plus class="w-4 h-4" />
            Add Card...
        </button>
    </div>
</template>


@pushOnce('component')
    <Script>
        const columnTemplate = document.querySelector("template#column");
        class Column {
            constructor(id, name, cards = []) {
                this.board = null;
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

                btnAdd.addEventListener("click", () => {
                    board.IS_EDITING = true;
                    btnAdd.style.display = "none";
                    formAdd.classList.remove("max-h-0");
                    inputCard.focus();
                });

                cardContainer.addEventListener("dragover", (e) => {
                    e.preventDefault();
                    let currentDraggingCard = DOM.find("div[data-role='card'].is-dragging");
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
                })

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
                    ServerRequest.post(`{{ url('/') }}/board/${board_id}/column/${column_id}/card`, {
                            name: cardValue
                        })
                        .then((response) => {
                            newCard.setId(response.data.id);
                            board.IS_EDITING = false;
                        });
                });

                for (const cardData of cards) {
                    const card = new Card(cardData.id, cardData.name);
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