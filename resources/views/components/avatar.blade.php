@props(['name', 'asset', 'size', 'action', 'href'])

@if (!isset($size))
    @php
        $size = 12;
    @endphp
@endif

@php
    $full_name = strtoupper($name);
    $initials = '';
    $name_array = explode(' ', $full_name);
    foreach ($name_array as $name) {
        $initials .= substr($name, 0, 1);
    }

    if (strlen($initials) >= 2) {
        $initials = substr($initials, 0, 2);
    }
@endphp

<div data-role="avatar"
    {{ $attributes->merge(['class' => 'relative aspect-square flex flex-wrap items-center justify-center gap-2 overflow-hidden text-lg font-semibold text-center text-white bg-black rounded-full cursor-default select-none border-2 border-gray-200']) }}>
    @if (isset($action) || isset($href))
        @if (isset($action))
            <div onclick="{{ $action }}" class="absolute top-0 left-0 z-10 w-full h-full">
                {{ $slot }}
            </div>
        @elseif (isset($href))
            <a href="{{ $href }}" class="absolute top-0 left-0 z-10 w-full h-full">
                {{ $slot }}
            </a>
        @endif
    @endif


    @if ($asset != null)
        <div
            class="flex items-center w-full h-full overflow-hidden bg-black rounded-full cursor-default select-none justify-center-center">
            <img class="object-fill" src="{{ asset($asset) }}" alt="profil_picture">
        </div>
    @else
        <div class="flex items-center w-full h-full justify-center-center">
            <p class="w-full text-center">{{ $initials }}</p>
        </div>
    @endif

</div>
