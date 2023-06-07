<template id="card">
    <div class="w-full px-4 py-2 mb-1 text-sm bg-white border border-gray-200 shadow cursor-pointer select-none line-clamp-3 rounded-xl">
    </div>
</template>

@pushOnce('component')
    <Script>
        const cardTemplate = document.querySelector("template#card");
        class Card {
            constructor(id, name, column) {
                const content = cardTemplate.content.cloneNode(true);
                const node = document.createElement("div");
                node.append(content);
                this.ref = node.children[0];

                this.ref.textContent = name;
                this.ref.dataset.id = id;
            }

            setId(id){
                this.ref.dataset.id = id;
            }

            mountTo(column) {
                column.ref.querySelector("section > div#card-container").append(this.ref);
            }

        }
    </Script>
@endPushOnce
