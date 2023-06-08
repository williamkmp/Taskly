@props(['teamid'])

<template id="card" class="!bg-gray-500">
    <div data-role="card" draggable="true"
        class="w-full px-4 py-2 text-sm bg-white border border-gray-200 cursor-pointer select-none line-clamp-3 rounded-xl">
    </div>
</template>

@pushOnce('component')
    <Script>
        const cardTemplate = document.querySelector("template#card");
        class Card {
            constructor(id, name, board) {
                this.board = board;
                const content = cardTemplate.content.cloneNode(true);
                const node = document.createElement("div");
                node.append(content);
                this.ref = node.children[0];

                this.ref.textContent = name;
                this.ref.dataset.id = id;
                this.ref.setAttribute('draggable', (id != null));

                this.ref.addEventListener("dragstart", () => {
                    this.board.IS_EDITING = true;
                    this.ref.classList.add("is-dragging");
                    this.ref.classList.toggle("!bg-gray-500");
                });

                this.ref.addEventListener("dragend", () => {
                    this.ref.classList.remove("is-dragging");
                    this.ref.setAttribute('draggable', false);
                    this.ref.classList.toggle("!bg-gray-500");

                    const board_id = this.board.ref.dataset.id;

                    ServerRequest.post(`{{ url('team/'.$teamid.'/board/${board_id}/card/reorder') }}`, {
                            column_id: this.ref.closest("div[data-role='column']").dataset.id,
                            middle_id: this.ref.dataset.id,
                            bottom_id: this.ref.nextElementSibling?.dataset?.id || null,
                            top_id: this.ref.previousElementSibling?.dataset?.id || null,
                        })
                        .then((response) => {
                            this.board.IS_EDITING = false;
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
                this.board = column.board;
            }

        }
    </Script>
@endPushOnce
