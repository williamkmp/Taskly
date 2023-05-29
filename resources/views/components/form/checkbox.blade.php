@props(['name', 'checked'])

<div data-role="form-checkbox" {{ $attributes->merge(['class' => 'flex items-center w-full ml-6 font-light']) }}>
    <label for="{{ $name }}" class="flex items-center w-full gap-2">
        <input type="checkbox" name="{{ $name }}" id="{{ $name }}"
            @isset($checked) checked @endisset>
        <div>
            {{ $slot }}
        </div>
    </label>
</div>
