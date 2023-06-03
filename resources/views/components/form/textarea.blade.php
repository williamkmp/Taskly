@props(['name', 'icon', 'label', 'placeholder', 'required', 'autofocus', 'value', 'rows'])

<div data-role="form-textarea"
    {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center w-full gap-2']) }}>
    @isset($label)
        <label for="textarea-{{ $name }}" class="w-full pl-6">{{ $label }}</label>
    @endisset
    <div class="flex items-center justify-center w-full gap-2 px-6 py-2 text-base border-2 border-black rounded-lg">
        @isset($icon)
            <div class="w-4 h-4">@svg($icon)</div>
        @endisset
        <textarea class="flex-grow outline-none resize-none"
            @isset($placeholder) placeholder="{{ $placeholder }}" @endisset name="{{ $name }}"
            id="textarea-{{ $name }}" @isset($required) required @endisset
            @isset($autofocus) autofocus @endisset
            @isset($rows) rows="{{ $rows }}" @endisset>{{ $slot }}</textarea>
    </div>
</div>