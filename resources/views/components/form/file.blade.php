@props(['name', 'icon', 'label', 'placeholder', 'required', 'value', 'multiple', 'accept'])


<div
data-role="form-file"
{{ $attributes->merge(['class' => 'flex flex-col items-center justify-center w-full gap-2']) }}>
    @isset($label)
        <label for="input-file-{{ $name }}" class="w-full pl-6">{{ $label }}</label>
    @endisset
    <input class="flex items-center justify-center w-full gap-2 px-6 py-2 text-base border-2 border-black rounded-full"
        type="file" id="input-file-{{ $name }}" name="{{ $name }}"
        @isset($accept) accept="{{ $accept }}" @endisset
        @isset($multiple) multiple @endisset />
</div>
