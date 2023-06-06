@props(["id", "name"])

<div
    id="column-{{ $id }}"
    data-id="{{ $id }}"
    data-name="{{ $name }}"
    class="group flex flex-col gap-2 h-full border-2 border-slate-50 shadow-lg w-[22rem] aspect-square rounded-xl py-2 px-4 pb-4 bg-slate-50">
    <header>
        <h2 class="w-full overflow-hidden text-lg font-bold truncate">{{ $name }}</h2>
    </header>
    <section class="w-full h-full overflow-hidden overflow-y-auto">
        <div>
            <div class="w-full px-4 py-2 bg-white border border-gray-200 shadow-md rounded-xl ">{{ 1 }}
                {{ $slot }}
            </div>
        </div>
    </section>
</div>
