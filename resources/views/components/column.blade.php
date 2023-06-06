@props(['id', 'name'])

<template id="column">
    <div
        class="group flex h-min flex-col gap-2 flex-shrink-0 max-h-full border-2 border-slate-50 shadow-lg w-[22rem] rounded-xl py-2 px-4 pb-4 bg-slate-50">
        <header>
            <h2 class="w-full overflow-hidden text-lg font-bold truncate"></h2>
        </header>
        <section class="w-full overflow-hidden overflow-y-auto">
            <div class="flex flex-col gap-2">
                <div class="w-full px-4 py-2 bg-white border border-gray-200 shadow rounded-xl ">{{ 1 }}
                    {{ $slot }}
                </div>
            </div>
        </section>
    </div>
</template>

@pushOnce('component')
    <Script>
        const columnTemplate = document.querySelector("template#column");
        class Column {
            constructor(board, id, name) {
                this.board = board;
                this.ref = columnTemplate.content.cloneNode("true");
                this.ref.querySelector("header > h2").textContent(name);
            }
        }
    </Script>
@endPushOnce
