@props(['name', 'asset', 'size', 'action'])

@if(!isset($size))
    @php
        $size = 12;
    @endphp
@endif

@php
    $full_name = $name;
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
    {{ $attributes->merge(['class' => 'relative flex flex-wrap items-center content-center gap-2 overflow-hidden text-lg font-semibold text-center text-white bg-black rounded-full cursor-default select-none']) }}>
    @isset($action)
        <a href="{{ $action }}" class="absolute top-0 left-0 z-10 flex flex-wrap items-center content-center w-full h-full transition-all bg-black opacity-0 hover:opacity-70">
            <x-fas-camera class="w-1/3 m-auto h-1/3"/>
        </a>
    @endisset


    @if ($asset != null)
        <div
            class="flex items-center content-center w-full h-full overflow-hidden bg-black rounded-full cursor-default select-none">
            <img class="object-fill" src="{{ asset($asset) }}" alt="profil_picture">
        </div>
    @else
        <div class="flex items-center content-center w-full h-full">
            <p class="w-full text-center">{{ $initials }}</p>
        </div>
    @endif

</div>
