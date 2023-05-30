@props(['primary', 'action', 'type', 'id', 'form'])

<button @isset($action) onclick="{{ $action }}" @endisset
    @isset($form) form="{{ $form }}" @endisset
    @isset($type) type="{{ $type }}" @endisset
    @isset($id) id="{{ $id }}" @endisset
    @if (isset($primary))
    {{ $attributes->merge(['class' => 'flex items-center justify-center w-full gap-2 px-6 py-1 text-base font-bold border-4 border-black rounded-full bg-black text-white hover:bg-white hover:text-black']) }}>
    @else
    {{ $attributes->merge(['class' => 'flex items-center justify-center w-full gap-2 px-6 py-1 text-base font-bold border-4 rounded-full text-black bg-slate-300 border-slate-300 hover:bg-black hover:text-white hover:border-black ']) }}>
    @endif
    {{ $slot }}
</button>


