<template id="card" class="!bg-gray-500">
    <div data-role="card"
        class="w-full px-4 py-2 text-sm bg-white border border-gray-200 shadow cursor-pointer select-none line-clamp-3 rounded-xl">
    </div>
</template>

@pushOnce('component')
    <Script>
        const cardTemplate = document.querySelector("template#card");
        class Card {
            constructor(id, name, column) {
                this.column = column;
                const content = cardTemplate.content.cloneNode(true);
                const node = document.createElement("div");
                node.append(content);
                this.ref = node.children[0];

                this.ref.textContent = name;
                this.ref.dataset.id = id;
                if (id != null) {
                    this.ref.setAttribute('draggable', true);
                }

                this.ref.addEventListener("dragstart", () => {
                    console.log(this.column.board.IS_EDITING);
                    this.column.board.IS_EDITING = true;
                    this.ref.classList.add("is-dragging");
                    this.ref.classList.toggle("!bg-gray-500");
                });

                this.ref.addEventListener("dragend", () => {
                    this.ref.classList.remove("is-dragging");
                    this.ref.setAttribute('draggable', false);
                    this.ref.classList.toggle("!bg-gray-500");

                    const board_id = this.column.board.ref.dataset.id;
                    const column_id = this.column.ref.dataset.id;

                    ServerRequest.post(`{{ url('/') }}/board/${board_id}/card/reorder`, {
                            column_id: this.ref.closest("div[data-role='column']").dataset.id,
                            middle_id: this.ref.dataset.id,
                            bottom_id: this.ref.nextSibling ? this.ref.nextSibling.dataset.id :
                                null
                        })
                        .then((response) => {
                            this.column.board.IS_EDITING = false;
                            this.ref.setAttribute('draggable', true);
                            console.log(response.data);
                        });
                })
            }

            setId(id) {
                this.ref.dataset.id = id;
                this.ref.setAttribute('draggable', true);
            }

            mountTo(column) {
                column.ref.querySelector("section > div#card-container").append(this.ref);
                this.column = column;
            }

        }
    </Script>
@endPushOnce
